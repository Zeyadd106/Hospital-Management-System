<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medication;
use App\Models\Payment;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display the shopping cart
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        $cartItems = [];
        $totalAmount = 0;
        
        // Get medication details for each cart item
        foreach ($cart as $id => $quantity) {
            $medication = Medication::find($id);
            if ($medication) {
                $subtotal = $medication->price * $quantity;
                $cartItems[] = [
                    'medication' => $medication,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal
                ];
                $totalAmount += $subtotal;
            }
        }
        
        return view('user.pharmacy.cart.index', compact('cartItems', 'totalAmount'));
    }
    
    /**
     * Add a medication to the cart
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        $request->validate([
            'medication_id' => 'required|exists:medications,id',
            'quantity' => 'sometimes|integer|min:1|max:10'
        ]);
        
        $medicationId = $request->medication_id;
        $quantity = $request->quantity ?? 1;
        
        // Get the medication
        $medication = Medication::findOrFail($medicationId);
        
        // Check if prescription is required
        if ($medication->prescription_required) {
            return redirect()->back()->with('error', 'This medication requires a prescription.');
        }
        
        // Check if in stock
        $stockLevel = $medication->stock ? $medication->stock->sum('current_stock') : 0;
        if ($stockLevel <= 0) {
            return redirect()->back()->with('error', 'This medication is out of stock.');
        }
        
        // Get current cart
        $cart = Session::get('cart', []);
        
        // Add to cart or update quantity
        if (isset($cart[$medicationId])) {
            $cart[$medicationId] += $quantity;
        } else {
            $cart[$medicationId] = $quantity;
        }
        
        // Ensure quantity doesn't exceed stock level
        if ($cart[$medicationId] > $stockLevel) {
            $cart[$medicationId] = $stockLevel;
            Session::put('cart', $cart);
            return redirect()->back()->with('warning', "Only {$stockLevel} units available. We've adjusted your cart.");
        }
        
        // Save cart to session
        Session::put('cart', $cart);
        
        return redirect()->route('user.pharmacy.cart.index')->with('success', "{$medication->name} added to your cart.");
    }
    
    /**
     * Update cart item quantity
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'medication_id' => 'required|exists:medications,id',
            'quantity' => 'required|integer|min:1|max:10'
        ]);
        
        $medicationId = $request->medication_id;
        $quantity = $request->quantity;
        
        // Get current cart
        $cart = Session::get('cart', []);
        
        // Check if item exists in cart
        if (!isset($cart[$medicationId])) {
            return redirect()->route('user.pharmacy.cart')->with('error', 'Item not found in your cart.');
        }
        
        // Get the medication
        $medication = Medication::findOrFail($medicationId);
        
        // Check stock level
        $stockLevel = $medication->stock ? $medication->stock->sum('current_stock') : 0;
        if ($quantity > $stockLevel) {
            $cart[$medicationId] = $stockLevel;
            Session::put('cart', $cart);
            return redirect()->route('user.pharmacy.cart')
                ->with('warning', "Only {$stockLevel} units available. We've adjusted your cart.");
        }
        
        // Update quantity
        $cart[$medicationId] = $quantity;
        Session::put('cart', $cart);
        
        return redirect()->route('user.pharmacy.cart')
            ->with('success', 'Cart updated successfully.');
    }
    
    /**
     * Remove an item from the cart
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove($id)
    {
        // Get current cart
        $cart = Session::get('cart', []);
        
        // Check if item exists in cart
        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
            return redirect()->route('user.pharmacy.cart')
                ->with('success', 'Item removed from cart.');
        }
        
        return redirect()->route('user.pharmacy.cart');
    }
    
    /**
     * Clear the entire cart
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear()
    {
        Session::forget('cart');
        return redirect()->route('user.pharmacy.cart')
            ->with('success', 'Your cart has been cleared.');
    }
    
    /**
     * Proceed to checkout
     *
     * @return \Illuminate\View\View
     */
    public function checkout()
    {
        $cart = Session::get('cart', []);
        
        // Redirect if cart is empty
        if (empty($cart)) {
            return redirect()->route('user.pharmacy.medications.index')
                ->with('error', 'Your cart is empty. Add some medications before checkout.');
        }
        
        $cartItems = [];
        $totalAmount = 0;
        
        // Get medication details for each cart item
        foreach ($cart as $id => $quantity) {
            $medication = Medication::find($id);
            if ($medication) {
                $subtotal = $medication->price * $quantity;
                $cartItems[] = [
                    'medication' => $medication,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal
                ];
                $totalAmount += $subtotal;
            }
        }
        
        return view('user.pharmacy.cart.checkout', compact('cartItems', 'totalAmount'));
    }
    
    /**
     * Process the order
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processOrder(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'delivery_address' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500'
        ]);
        
        $cart = Session::get('cart', []);
        
        // Redirect if cart is empty
        if (empty($cart)) {
            return redirect()->route('user.pharmacy.medications.index')
                ->with('error', 'Your cart is empty. Add some medications before checkout.');
        }
        
        $totalAmount = 0;
        $orderItems = [];
        
        // Calculate total and prepare order items
        foreach ($cart as $id => $quantity) {
            $medication = Medication::find($id);
            if ($medication) {
                $subtotal = $medication->price * $quantity;
                $totalAmount += $subtotal;
                $orderItems[] = [
                    'medication_id' => $id,
                    'quantity' => $quantity,
                    'price' => $medication->price,
                    'subtotal' => $subtotal
                ];
            }
        }
        
        // Create payment
        $payment = new Payment();
        $payment->user_id = Auth::id();
        $payment->payment_method_id = $request->payment_method_id;
        $payment->amount = $totalAmount;
        $payment->status = 'pending';
        $payment->description = 'Medication purchase';
        $payment->save();
        
        // Create pharmacy order
        $order = new \App\Models\PharmacyOrder();
        $order->user_id = Auth::id();
        $order->payment_id = $payment->id;
        $order->total_amount = $totalAmount;
        $order->delivery_address = $request->delivery_address;
        $order->notes = $request->notes;
        $order->status = 'pending';
        $order->save();
        
        // Save order items
        foreach ($orderItems as $item) {
            $orderItem = new \App\Models\PharmacyOrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->medication_id = $item['medication_id'];
            $orderItem->quantity = $item['quantity'];
            $orderItem->price = $item['price'];
            $orderItem->subtotal = $item['subtotal'];
            $orderItem->save();
            
            // Update stock
            $medication = Medication::find($item['medication_id']);
            if ($medication && $medication->stock) {
                foreach ($medication->stock as $stock) {
                    if ($stock->current_stock > 0) {
                        $reduceAmount = min($stock->current_stock, $item['quantity']);
                        $stock->current_stock -= $reduceAmount;
                        $stock->save();
                        
                        $item['quantity'] -= $reduceAmount;
                        if ($item['quantity'] <= 0) {
                            break;
                        }
                    }
                }
            }
        }
        
        // Clear the cart
        Session::forget('cart');
        
        // Redirect to payment page
        return redirect()->route('payments.create', ['order_id' => $order->id])
            ->with('success', 'Your order has been placed. Please complete the payment.');
    }
}
