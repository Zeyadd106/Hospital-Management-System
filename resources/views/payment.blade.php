<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Payment Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #1d3557;
            --secondary-color: #457b9d;
            --accent-color: #a8dadc;
            --background-light: #f1faee;
            --text-color: #1d3557;
        }

        body {
            background-color: var(--background-light);
            color: var(--text-color);
        }

        .sidebar {
            background-color: var(--primary-color);
            min-height: 100vh;
            position: fixed;
            width: 250px;
            padding: 1rem;
            z-index: 1000;
        }

        .sidebar .nav-link {
            color: white;
            padding: 0.5rem 1rem;
            margin: 0.2rem 0;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: var(--secondary-color);
        }

        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .btn-custom {
            background-color: var(--primary-color);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: var(--secondary-color);
            color: white;
        }

        .table-custom thead {
            background-color: var(--primary-color);
            color: white;
        }

        .table-custom th {
            border: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <h2 class="text-white text-center mb-4">Bill Payment</h2>
        <div class="nav flex-column">
            <a href="{{ route('home') }}" class="nav-link">
                <i class="bi bi-house-door me-2"></i>Home
            </a>
            {{-- <a href="{{ route('blog') }}" class="nav-link">
                <i class="bi bi-people me-2"></i>Blog
            </a>
            <a href="{{ route('appointment') }}" class="nav-link">
                <i class="bi bi-calendar"></i> Appointment
            </a>
            <a href="{{ route('service') }}" class="nav-link">
                <i class="bi bi-clipboard2-heart"></i> Services
            </a>
            <a href="{{ route('doctors') }}" class="nav-link">
                <i class="bi bi-person-fill"></i> Doctors
            </a>
            <a href="{{ route('pharmacy') }}" class="nav-link">
                <i class="bi bi-capsule me-2"></i>Pharmacy
            </a>
            <a href="{{ route('clinics') }}" class="nav-link">
                <i class="bi bi-clipboard2-pulse me-2"></i>Outpatient Clinics
            </a> --}}
            <a href="{{ route('payment') }}" class="nav-link active">
                <i class="bi bi-credit-card me-2"></i>Payment
            </a>
            {{-- <a href="{{ route('message') }}" class="nav-link">
                <i class="bi bi-chat-left-dots"></i> Contact us
            </a> --}}
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <h1 class="mb-4">Payment Page</h1>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Patient Information -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Patient Information</h5>
                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="patientName" class="form-label">Patient Name</label>
                            <input type="text" class="form-control" id="patientName" required>
                        </div>
                        <div class="col-md-6">
                            <label for="patientId" class="form-label">Patient ID</label>
                            <input type="text" class="form-control" id="patientId" required>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Charges -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Charges</h5>
                <table class="table table-hover table-custom">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Description</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Pharmacy</td>
                            <td>Prescription Medications</td>
                            <td>$150.00</td>
                        </tr>
                        <tr>
                            <td>Laboratory</td>
                            <td>Blood Tests</td>
                            <td>$200.00</td>
                        </tr>
                        <tr>
                            <td>Clinic</td>
                            <td>Consultation Fee</td>
                            <td>$100.00</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="2">Total</td>
                            <td>$450.00</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Payment Method -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Payment Method</h5>
                <form action="{{ route('payment.process') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Select Payment Method</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="creditCard" value="credit" checked>
                            <label class="form-check-label" for="creditCard">
                                Credit Card
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="debitCard" value="debit">
                            <label class="form-check-label" for="debitCard">
                                Debit Card
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="insurance" value="insurance">
                            <label class="form-check-label" for="insurance">
                                Insurance
                            </label>
                        </div>
                    </div>
                    <div id="cardDetails">
                        <div class="mb-3">
                            <label for="cardNumber" class="form-label">Card Number</label>
                            <input type="text" class="form-control" name="cardNumber" id="cardNumber" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="expiryDate" class="form-label">Expiry Date</label>
                                <input type="text" class="form-control" name="expiryDate" id="expiryDate" placeholder="MM/YY" required>
                            </div>
                            <div class="col-md-6">
                                <label for="cvv" class="form-label">CVV</label>
                                <input type="text" class="form-control" name="cvv" id="cvv" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-custom">Process Payment</button>
                </form>
            </div>
        </div>

        <!-- Receipt -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Receipt</h5>
                <div class="alert alert-success" role="alert">
                    Payment successful! Receipt number: #12345
                </div>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Patient Name:</th>
                            <td>John Doe</td>
                        </tr>
                        <tr>
                            <th>Patient ID:</th>
                            <td>P12345</td>
                        </tr>
                        <tr>
                            <th>Date:</th>
                            <td>January 18, 2025</td>
                        </tr>
                        <tr>
                            <th>Total Amount Paid:</th>
                            <td>$450.00</td>
                        </tr>
                        <tr>
                            <th>Payment Method:</th>
                            <td>Credit Card (ending in 1234)</td>
                        </tr>
                    </tbody>
                </table>
                <button class="btn btn-custom mt-3">
                    <i class="bi bi-printer me-2"></i>Print Receipt
                </button>
            </div>
        </div>
    </main>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

