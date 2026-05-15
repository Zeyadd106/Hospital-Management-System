<?php

namespace App\Http\Controllers;

use App\Models\PharmacyStock;
use Illuminate\Http\Request;

class PharmacyStockController extends Controller
{
    /**
     * Display a listing of pharmacy stock.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $stocks = PharmacyStock::paginate(10);
        return view('pharmacy.stock.index', compact('stocks'));
    }

    /**
     * Store a newly created stock item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'medicine_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'expiry_date' => 'required|date',
            'price' => 'required|numeric|min:0'
        ]);

        PharmacyStock::create($validatedData);

        return redirect()->route('pharmacy.stock.index')
            ->with('success', 'Stock item added successfully.');
    }

    /**
     * Show the form for editing a stock item.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $stock = PharmacyStock::findOrFail($id);
        return view('pharmacy.stock.edit', compact('stock'));
    }

    /**
     * Update the specified stock item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'medicine_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'expiry_date' => 'required|date',
            'price' => 'required|numeric|min:0'
        ]);

        $stock = PharmacyStock::findOrFail($id);
        $stock->update($validatedData);

        return redirect()->route('pharmacy.stock.index')
            ->with('success', 'Stock item updated successfully.');
    }

    /**
     * Remove the specified stock item.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $stock = PharmacyStock::findOrFail($id);
        $stock->delete();

        return redirect()->route('pharmacy.stock.index')
            ->with('success', 'Stock item deleted successfully.');
    }

    /**
     * Add stock to a medication
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function addStock(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'quantity' => 'required|integer|min:1',
                'batch_number' => 'required|string|max:100',
                'expiry_date' => 'required|date|after:today',
                'purchase_price' => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0',
            ]);
            
            $medication = \App\Models\Medication::findOrFail($id);
            $pharmacyId = auth()->user()->pharmacy_id;
            
            // Find or create stock item
            $stockItem = $medication->stockItems()
                ->where('pharmacy_id', $pharmacyId)
                ->firstOrNew([], [
                    'pharmacy_id' => $pharmacyId,
                    'current_stock' => 0,
                    'min_stock' => 10, // Default value, can be adjusted
                ]);
            
            $isNew = !$stockItem->exists;
            $previousQuantity = $stockItem->current_stock;
            $addedQuantity = (int)$validatedData['quantity'];
            $newQuantity = $previousQuantity + $addedQuantity;
            
            // Update stock item details
            $stockItem->current_stock = $newQuantity;
            $stockItem->batch_number = $validatedData['batch_number'];
            $stockItem->expiry_date = $validatedData['expiry_date'];
            $stockItem->purchase_price = $validatedData['purchase_price'];
            $stockItem->selling_price = $validatedData['selling_price'];
            
            // Set initial min_stock if this is a new stock item
            if ($isNew) {
                $stockItem->min_stock = 10; // Default value, can be adjusted
            }
            
            $stockItem->save();
            
            // Log the stock addition
            \App\Models\StockAdjustment::create([
                'stock_item_id' => $stockItem->id,
                'user_id' => auth()->id(),
                'adjustment_type' => 'add',
                'quantity' => $addedQuantity,
                'previous_quantity' => $previousQuantity,
                'new_quantity' => $newQuantity,
                'reason' => 'Stock added',
            ]);
            
            // Update medication prices if this is the first stock addition
            if ($isNew || !$medication->purchase_price || !$medication->price) {
                $medication->purchase_price = $validatedData['purchase_price'];
                $medication->price = $validatedData['selling_price'];
                $medication->save();
            }
            
            return response()->json([
                'success' => true,
                'message' => "Successfully added $addedQuantity units to stock. New quantity: $newQuantity"
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding stock: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Adjust the stock level of a medication
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function adjustStock(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'type' => 'required|in:add,remove,set',
                'amount' => 'required|integer|min:0',
                'reason' => 'required|string|max:500',
                'batch_number' => 'nullable|string|max:100',
                'expiry_date' => 'nullable|date|after:today',
            ]);
            
            $medication = \App\Models\Medication::findOrFail($id);
            $pharmacyId = auth()->user()->pharmacy_id;
            
            // Find or create stock item
            $stockItem = $medication->stockItems()
                ->where('pharmacy_id', $pharmacyId)
                ->firstOrNew([], [
                    'pharmacy_id' => $pharmacyId,
                    'current_stock' => 0,
                    'min_stock' => 10, // Default value, can be adjusted
                ]);
            
            $previousQuantity = $stockItem->current_stock;
            $adjustmentType = $validatedData['type'];
            $adjustmentAmount = (int)$validatedData['amount'];
            
            // Update stock based on adjustment type
            switch ($adjustmentType) {
                case 'add':
                    $newQuantity = $previousQuantity + $adjustmentAmount;
                    $action = 'added';
                    break;
                    
                case 'remove':
                    if ($previousQuantity < $adjustmentAmount) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Insufficient stock available for this adjustment.'
                        ], 422);
                    }
                    $newQuantity = $previousQuantity - $adjustmentAmount;
                    $action = 'removed';
                    break;
                    
                case 'set':
                    $newQuantity = $adjustmentAmount;
                    $action = 'set';
                    break;
                    
                default:
                    throw new \Exception('Invalid adjustment type');
            }
            
            // Update stock item details
            $stockItem->current_stock = $newQuantity;
            
            if (!empty($validatedData['batch_number'])) {
                $stockItem->batch_number = $validatedData['batch_number'];
            }
            
            if (!empty($validatedData['expiry_date'])) {
                $stockItem->expiry_date = $validatedData['expiry_date'];
            }
            
            $stockItem->save();
            
            // Log the stock adjustment
            \App\Models\StockAdjustment::create([
                'stock_item_id' => $stockItem->id,
                'user_id' => auth()->id(),
                'adjustment_type' => $adjustmentType,
                'quantity' => $adjustmentAmount,
                'previous_quantity' => $previousQuantity,
                'new_quantity' => $newQuantity,
                'reason' => $validatedData['reason'],
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Stock level has been successfully $action. New quantity: $newQuantity"
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adjusting stock: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the stock history for a medication
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function stockHistory($id)
    {
        $medication = \App\Models\Medication::findOrFail($id);
        $pharmacyId = auth()->user()->pharmacy_id;
        
        // Get the stock item for this medication and pharmacy
        $stockItem = $medication->stockItems()
            ->where('pharmacy_id', $pharmacyId)
            ->with(['adjustments' => function($query) {
                $query->with('user')->latest();
            }])
            ->firstOrFail();
        
        // Get paginated adjustments
        $adjustments = $stockItem->adjustments()
            ->with('user')
            ->latest()
            ->paginate(15);
        
        // Calculate summary statistics
        $totalAdded = $stockItem->adjustments()
            ->where('adjustment_type', 'add')
            ->sum('quantity');
            
        $totalRemoved = $stockItem->adjustments()
            ->where('adjustment_type', 'remove')
            ->sum('quantity');
            
        $totalAdjusted = $stockItem->adjustments()
            ->where('adjustment_type', 'set')
            ->count();
        
        $summary = [
            'total_added' => $totalAdded,
            'total_removed' => $totalRemoved,
            'net_change' => $totalAdded - $totalRemoved,
            'total_adjustments' => $adjustments->total(),
            'total_adjusted' => $totalAdjusted,
        ];
        
        return view('pharmacy.medications.stock-history', compact('medication', 'stockItem', 'adjustments', 'summary'));
    }
}
