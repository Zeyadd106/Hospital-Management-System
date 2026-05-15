<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .receipt {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #1d3557;
            padding-bottom: 10px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #1d3557;
        }
        .receipt-title {
            font-size: 20px;
            margin: 10px 0;
        }
        .receipt-id {
            font-size: 14px;
            color: #666;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            width: 150px;
            font-weight: bold;
        }
        .info-value {
            flex: 1;
        }
        .payment-details {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .amount {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .status-completed {
            color: #28a745;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <div class="logo">MediCare</div>
            <div class="receipt-title">Payment Receipt</div>
            <div class="receipt-id">Receipt #{{ $payment->id }}</div>
        </div>
        
        <div class="info-section">
            <div class="info-row">
                <div class="info-label">Date:</div>
                <div class="info-value">{{ $payment->created_at->format('F d, Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Transaction ID:</div>
                <div class="info-value">{{ $payment->transaction_id ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value status-completed">{{ ucfirst($payment->status) }}</div>
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-row">
                <div class="info-label">Customer:</div>
                <div class="info-value">{{ $payment->user->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $payment->user->email }}</div>
            </div>
        </div>
        
        <div class="payment-details">
            <div class="info-row">
                <div class="info-label">Payment Method:</div>
                <div class="info-value">
                    @if($payment->paymentMethod)
                        @if($payment->paymentMethod->type == 'credit_card')
                            Credit Card ({{ $payment->paymentMethod->card_number }})
                        @elseif($payment->paymentMethod->type == 'debit_card')
                            Debit Card ({{ $payment->paymentMethod->card_number }})
                        @elseif($payment->paymentMethod->type == 'bank_transfer')
                            Bank Transfer ({{ $payment->paymentMethod->bank_name }})
                        @else
                            {{ ucfirst(str_replace('_', ' ', $payment->paymentMethod->type)) }}
                        @endif
                    @else
                        Not specified
                    @endif
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Description:</div>
                <div class="info-value">{{ $payment->description }}</div>
            </div>
            
            @if($payment->appointment_id)
            <div class="info-row">
                <div class="info-label">Service:</div>
                <div class="info-value">Appointment</div>
            </div>
            @elseif($payment->vaccination_booking_id)
            <div class="info-row">
                <div class="info-label">Service:</div>
                <div class="info-value">Vaccination</div>
            </div>
            @elseif($payment->health_screening_booking_id)
            <div class="info-row">
                <div class="info-label">Service:</div>
                <div class="info-value">Health Screening</div>
            </div>
            @endif
        </div>
        
        <div class="amount">
            <div class="info-row">
                <div class="info-label">Total Amount:</div>
                <div class="info-value">${{ number_format($payment->amount, 2) }}</div>
            </div>
        </div>
        
        <div class="footer">
            <p>Thank you for choosing MediCare for your healthcare needs.</p>
            <p>This is an electronically generated receipt and does not require a signature.</p>
            <p>&copy; {{ date('Y') }} MediCare. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
