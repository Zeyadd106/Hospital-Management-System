<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Display a listing of the user's payments.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $payments = Payment::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $paymentMethods = PaymentMethod::where('user_id', $user->id)->get();
        
        return view('payments.create', compact('paymentMethods'));
    }

    /**
     * Store a newly created payment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Validate the request
            $rules = [
                'amount' => 'required|numeric|min:1',
                'description' => 'required|string|max:255',
                'terms' => 'required',
            ];
            
            // Only validate payment_method_id if it's not a fake payment
            if ($request->payment_method_id !== 'fake_payment') {
                $rules['payment_method_id'] = 'required|exists:payment_methods,id';
            }
            
            $validated = $request->validate($rules);
            
            $user = Auth::user();
            
            $payment = new Payment();
            $payment->user_id = $user->id;
            $payment->amount = $request->amount;
            $payment->description = $request->description;
            
            // Handle fake payment method
            if ($request->payment_method_id === 'fake_payment') {
                $payment->status = 'completed';
                $payment->transaction_id = 'DEMO-' . strtoupper(Str::random(8));
            } else {
                $payment->payment_method_id = $request->payment_method_id;
                $payment->status = 'pending';
            }
            
            $payment->save();
            
            // For demo purposes, automatically complete the payment
            if ($request->payment_method_id !== 'fake_payment') {
                $payment->status = 'completed';
                $payment->save();
            }
            
            return redirect()->route('payments.show', $payment->id)
                ->with('success', 'Payment processed successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return with validation errors
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            // Log the error and return with a generic error message
            \Log::error('Error processing payment: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while processing your payment. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified payment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $payment = Payment::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
            
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for managing payment methods.
     *
     * @return \Illuminate\Http\Response
     */
    public function methods()
    {
        $user = Auth::user();
        $paymentMethods = PaymentMethod::where('user_id', $user->id)->get();
        
        return view('payments.methods', compact('paymentMethods'));
    }

    /**
     * Store a new payment method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMethod(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'type' => 'required|string|in:credit_card,debit_card,bank_transfer',
                'card_number' => 'required_if:type,credit_card,debit_card|string|max:19',
                'expiry_date' => 'required_if:type,credit_card,debit_card|string|max:7',
                'cvv' => 'required_if:type,credit_card,debit_card|string|max:4',
                'bank_name' => 'required_if:type,bank_transfer|string|max:255',
                'account_number' => 'required_if:type,bank_transfer|string|max:20',
            ]);
            
            $user = Auth::user();
            
            $paymentMethod = new PaymentMethod();
            $paymentMethod->user_id = $user->id;
            $paymentMethod->type = $request->type;
            
            if ($request->type == 'credit_card' || $request->type == 'debit_card') {
                // Mask the card number for security
                $maskedNumber = substr($request->card_number, -4);
                $paymentMethod->card_number = '************' . $maskedNumber;
                $paymentMethod->expiry_date = $request->expiry_date;
                // Don't store CVV for security reasons
            } else if ($request->type == 'bank_transfer') {
                $paymentMethod->bank_name = $request->bank_name;
                $paymentMethod->account_number = $request->account_number;
            }
            
            $paymentMethod->save();
            
            // Redirect back to the previous page (checkout)
            return redirect()->back()
                ->with('success', 'Payment method added successfully.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return with validation errors
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            // Log the error and return with a generic error message
            \Log::error('Error adding payment method: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while adding your payment method. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified payment method.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyMethod($id)
    {
        $user = Auth::user();
        $paymentMethod = PaymentMethod::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
            
        $paymentMethod->delete();
        
        return redirect()->route('payments.methods')
            ->with('success', 'Payment method removed successfully.');
    }

    /**
     * Download a PDF receipt for the payment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function downloadReceipt($id)
    {
        $user = Auth::user();
        $payment = Payment::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
            
        // Only allow downloading receipts for completed payments
        if ($payment->status != 'completed') {
            return redirect()->route('payments.show', $payment->id)
                ->with('error', 'You can only download receipts for completed payments.');
        }
        
        // Generate PDF
        $pdf = PDF::loadView('payments.receipt', compact('payment'));
        
        // Set filename
        $filename = 'receipt_' . $payment->id . '_' . date('Y-m-d') . '.pdf';
        
        // Download the PDF
        return $pdf->download($filename);
    }
}
