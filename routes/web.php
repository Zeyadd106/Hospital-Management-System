<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Public Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\DoctorMessageController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\VaccinationBookingController;
use App\Http\Controllers\HealthScreeningBookingController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\DoctorProfileController;
use App\Http\Controllers\PharmacyDashboardController;
use App\Http\Controllers\PharmacyStockController;
use App\Http\Controllers\PharmacyReportController;
use App\Http\Controllers\PharmacyMedicationController;
use App\Http\Controllers\HealthScreeningController;
use App\Http\Controllers\VaccinationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserMessageController;
use App\Http\Controllers\DoctorVaccinationController;
use App\Http\Controllers\DoctorHealthScreeningController;
use App\Http\Controllers\UserPharmacyController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DoctorPrescriptionController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DoctorController as AdminDoctorController;
use App\Http\Controllers\Admin\ClinicController as AdminClinicController;
use App\Http\Controllers\Admin\VaccinationBookingController as AdminVaccinationController;
use App\Http\Controllers\Admin\DepartmentController as AdminDepartmentController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AdminMessageController as AdminMessagesController;
use App\Http\Controllers\Admin\HealthScreeningController as AdminHealthScreeningsController;

// ==================== Landing Page ====================
Route::get('/', [LandingController::class, 'index'])->name('landing');

// ==================== Public Routes ====================
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServiceController::class, 'index'])->name('services');
Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');

// Public Pharmacy Routes
Route::prefix('pharmacy')->name('pharmacy.')->group(function () {
    Route::get('/', [PharmacyController::class, 'index'])->name('index');
    Route::get('/search', [PharmacyController::class, 'search'])->name('search');
    Route::get('/search-medicine', [PharmacyController::class, 'searchMedicine'])->name('searchMedicine');
    
    // Medication routes
    Route::get('/medications', [PharmacyController::class, 'medications'])->name('medications')->withoutMiddleware(['auth', 'role']);
    Route::get('/medications/{id}', [PharmacyController::class, 'showMedication'])->name('medications.show')->where('id', '[0-9]+')->withoutMiddleware(['auth', 'role']);
    
    // Cart routes
    Route::get('/cart', [PharmacyController::class, 'viewCart'])->name('cart');
    Route::post('/cart/add', [PharmacyController::class, 'addMedicationToCart'])->name('cart.add');
    Route::post('/cart/update', [PharmacyController::class, 'updateCart'])->name('cart.update');
    Route::get('/cart/remove/{id}', [PharmacyController::class, 'removeFromCart'])->name('cart.remove');
    
    // Legacy routes for Medicine model
    Route::post('/medicine/{medicine}/add-to-cart', [PharmacyController::class, 'addMedicineToCart'])->name('medicine.addToCart');
    
    // Pharmacy detail route - must be after other specific routes
    Route::get('/{id}', [PharmacyController::class, 'show'])->name('show')->where('id', '[0-9]+');
    
    // Public Prescription Refill Routes
    Route::get('/prescription-refill', [PharmacyController::class, 'showPrescriptionRefillForm'])->name('prescription-refill');
    Route::post('/prescription-refill', [PharmacyController::class, 'storePrescriptionRefill'])->name('prescription-refill.store');
    Route::get('/prescription-refill/confirmation/{code}', [PharmacyController::class, 'prescriptionRefillConfirmation'])->name('prescription-refill.confirmation');
});

// Public Department Routes
Route::get('/departments', [PageController::class, 'departments'])->name('departments');
Route::get('/departments/{id}', [DepartmentController::class, 'show'])->name('departments.show');

// Public Doctor Routes
Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
Route::get('/doctors/{id}', [DoctorController::class, 'show'])->name('doctors.show');
Route::get('/doctors/{id}/book', [DoctorController::class, 'bookAppointment'])->name('doctors.book');

// Public Clinic Routes
Route::get('/clinics', [ClinicController::class, 'index'])->name('clinics');
Route::get('/clinics/{id}', [ClinicController::class, 'show'])->name('clinics.show');

// Public Contact Routes
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/contact/success', [ContactController::class, 'success'])->name('contact.success');
Route::get('/contact/doctors-by-department/{department}', [ContactController::class, 'getDoctorsByDepartment']);

// Public Miscellaneous Routes
Route::get('/blog', [PageController::class, 'blog'])->name('blog');

// Public Vaccination Routes (Unauthenticated access)
Route::prefix('vaccinations')->name('vaccination.')->group(function () {
    Route::get('/', [VaccinationController::class, 'index'])->name('index');
    
    // Booking routes with multiple names for flexibility
    Route::get('/book', [VaccinationBookingController::class, 'create'])->name('book.create');
    Route::post('/book', [VaccinationBookingController::class, 'store'])->name('book.store');
    Route::post('/bookings', [VaccinationBookingController::class, 'store'])->name('bookings.store');
    
    Route::get('/reminders', [VaccinationController::class, 'reminders'])->name('reminders');
    
    // Booking management routes
    Route::prefix('bookings')->name('vaccination.booking.')->middleware('auth')->group(function() {
        Route::get('/', [VaccinationBookingController::class, 'index'])->name('index');
        Route::get('/create', [VaccinationBookingController::class, 'create'])->name('create');
        Route::post('/', [VaccinationBookingController::class, 'store'])->name('store');
        Route::get('/{id}', [VaccinationBookingController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [VaccinationBookingController::class, 'edit'])->name('edit');
        Route::put('/{id}', [VaccinationBookingController::class, 'update'])->name('update');
        Route::get('/{id}/verify', [VaccinationBookingController::class, 'verify'])->name('verify');
        Route::post('/{id}/confirm', [VaccinationBookingController::class, 'confirm'])->name('confirm');
        Route::post('/{id}/cancel', [VaccinationBookingController::class, 'cancel'])->name('cancel');
    });
    
    Route::get('/{id}', [VaccinationController::class, 'show'])->name('details');
});

// Vaccinations routes for profile
Route::prefix('vaccinations')->name('vaccinations.')->middleware('auth')->group(function () {
    Route::get('/{id}', [VaccinationBookingController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [VaccinationBookingController::class, 'edit'])->name('edit');
    Route::delete('/{id}', [VaccinationBookingController::class, 'destroy'])->name('destroy');
});

// Public Health Screening Routes (Unauthenticated access)
Route::prefix('health-screenings')->name('health-screenings.public.')->group(function () {
    Route::get('/', [HealthScreeningController::class, 'publicIndex'])->name('index');
    Route::get('/reminders', [HealthScreeningController::class, 'reminders'])->name('reminders');
});

// Health Screenings Routes
Route::prefix('health-screenings')->name('health-screenings.')->group(function () {
    Route::get('/', [HealthScreeningController::class, 'index'])->name('index');
    Route::get('/booking/create', [HealthScreeningBookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [HealthScreeningBookingController::class, 'store'])->name('booking.store');
});

// Vaccination Routes
Route::prefix('vaccinations')->name('vaccinations.')->group(function () {
    Route::get('/', [VaccinationController::class, 'index'])->name('index');
    Route::get('/booking/create', [VaccinationBookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [VaccinationBookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/verify', [VaccinationBookingController::class, 'verify'])->name('booking.verify');
    Route::get('/booking/{id}', [VaccinationBookingController::class, 'show'])->name('booking.show');
    Route::patch('/booking/{id}/cancel', [VaccinationBookingController::class, 'cancel'])->name('booking.cancel');
});

// Authentication Routes
Auth::routes([
    'verify' => true,
    'register' => true,
    'reset' => true,
    'confirm' => true,
    'login' => true
]);

// Override default registration redirect
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register');
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register.show');

// Override default login redirect
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login.show');

// Password Reset Routes
Route::prefix('password')->group(function () {
    // Show password reset request form
    Route::get('/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    
    // Send password reset email
    Route::post('/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    
    // Show password reset form
    Route::get('/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    
    // Process password reset
    Route::post('/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Social Authentication Routes
Route::get('/auth/{provider}', [SocialAuthController::class, 'redirect'])->name('auth.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'handleCallback'])->name('auth.callback');

// ==================== Authenticated Routes ====================
Route::middleware(['auth'])->group(function () {
    // Dashboard Routes
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/user-dashboard', [HomeController::class, 'dashboard'])->name('user.dashboard');
    
    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
    
    // Pharmacy Routes
    Route::prefix('pharmacy')->middleware(['role:pharmacy_admin'])->name('pharmacy.')->group(function () {
        Route::get('/dashboard', [PharmacyDashboardController::class, 'index'])->name('dashboard');
        
        // Medication Management Routes
        Route::prefix('medications')->name('medications.')->group(function () {
            Route::get('/', [PharmacyMedicationController::class, 'index'])->name('index');
            Route::get('/create', [PharmacyMedicationController::class, 'create'])->name('create');
            Route::post('/', [PharmacyMedicationController::class, 'store'])->name('store');
            Route::get('/{medication}', [PharmacyMedicationController::class, 'show'])->name('show');
            Route::get('/{medication}/edit', [PharmacyMedicationController::class, 'edit'])->name('edit');
            Route::put('/{medication}', [PharmacyMedicationController::class, 'update'])->name('update');
            Route::delete('/{medication}', [PharmacyMedicationController::class, 'destroy'])->name('destroy');
            
            // Stock Management
            Route::post('/{medication}/stock', [PharmacyStockController::class, 'addStock'])->name('stock.add');
            Route::post('/{medication}/adjust-stock', [PharmacyStockController::class, 'adjustStock'])->name('stock.adjust');
            
            // Stock History
            Route::get('/{medication}/stock/history', [PharmacyStockController::class, 'stockHistory'])->name('stock.history');
        });

        // Search Medicine Route
        Route::get('/search-medicine', [PharmacyMedicationController::class, 'search'])->name('searchMedicine');
        
        // Reports
        Route::get('/reports', [PharmacyReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/sales', [PharmacyReportController::class, 'salesReport'])->name('reports.sales');
        Route::get('/reports/stock', [PharmacyReportController::class, 'stockReport'])->name('reports.stock');
        
        // Prescription Refills
        Route::get('/prescription-refills', [PharmacyDashboardController::class, 'myPrescriptionRefills'])->name('prescription-refills.index');
        Route::get('/prescription-refills/{id}/approve', [PharmacyDashboardController::class, 'approvePrescriptionRefill'])->name('prescription-refill.approve');
        Route::get('/prescription-refills/{id}/reject', [PharmacyDashboardController::class, 'rejectPrescriptionRefill'])->name('prescription-refill.reject');
        Route::get('/prescription-refills/{id}/process', [PharmacyDashboardController::class, 'processPrescriptionRefill'])->name('prescription-refill.process');
        
        // Medication Management
        Route::get('/medication-management', [PharmacyDashboardController::class, 'medicationManagement'])->name('medication-management');
    });
    
    // User Pharmacy Routes
    Route::middleware(['auth'])->prefix('user/pharmacy')->name('user.pharmacy.')->group(function () {
        // Medication routes
        Route::get('/medications', [UserPharmacyController::class, 'listMedications'])->name('medications.index');
        Route::get('/medications/search', [UserPharmacyController::class, 'searchMedications'])->name('medications.search');
        Route::get('/medications/{id}', [UserPharmacyController::class, 'showMedication'])->name('medications.show');
        
        // Cart routes
        Route::get('/cart', [CartController::class, 'index'])->name('cart');
        Route::prefix('cart')->name('cart.')->group(function () {
            Route::get('/', [CartController::class, 'index'])->name('index');
            Route::post('/add', [CartController::class, 'add'])->name('add');
            Route::post('/update', [CartController::class, 'update'])->name('update');
            Route::get('/remove/{id}', [CartController::class, 'remove'])->name('remove');
            Route::get('/clear', [CartController::class, 'clear'])->name('clear');
            Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
            Route::post('/process', [CartController::class, 'processOrder'])->name('process');
        });
        
        // Prescription Refills
        Route::get('/prescription-refill', [UserPharmacyController::class, 'showPrescriptionRefillForm'])->name('prescription-refill.create');
        Route::post('/prescription-refill', [UserPharmacyController::class, 'storePrescriptionRefill'])->name('prescription-refill.store');
        Route::get('/prescription-refills', [UserPharmacyController::class, 'myPrescriptionRefills'])->name('prescription-refills.index');
        Route::get('/prescription-refills/{id}', [UserPharmacyController::class, 'showPrescriptionRefill'])->name('prescription-refills.show');
    });
    
    // Notifications Routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/{id}', [NotificationController::class, 'show'])->name('show');
        Route::post('/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
        Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });
    
    // Payments Routes
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/create', [PaymentController::class, 'create'])->name('create');
        Route::post('/', [PaymentController::class, 'store'])->name('store');
        Route::get('/methods', [PaymentController::class, 'methods'])->name('methods');
        Route::post('/methods', [PaymentController::class, 'storeMethod'])->name('methods.store');
        Route::delete('/methods/{id}', [PaymentController::class, 'destroyMethod'])->name('methods.destroy');
        Route::get('/{id}/download', [PaymentController::class, 'downloadReceipt'])->name('download');
        Route::get('/{id}', [PaymentController::class, 'show'])->where('id', '[0-9]+')->name('show');
    });
    
    // Vaccination Routes (Authenticated)
    Route::prefix('vaccinations')->name('vaccination.')->group(function () {
        Route::get('/', [VaccinationController::class, 'index'])->name('index');
        Route::get('/create', [VaccinationController::class, 'create'])->name('create');
        Route::post('/', [VaccinationController::class, 'store'])->name('store');
        Route::get('/bookings', [VaccinationController::class, 'bookings'])->name('bookings');
        Route::get('/booking/{id}', [VaccinationController::class, 'showBooking'])->name('booking.show');
        Route::get('/booking/{id}/edit', [VaccinationController::class, 'editBooking'])->name('booking.edit');
        Route::put('/booking/{id}', [VaccinationController::class, 'updateBooking'])->name('booking.update');
        Route::get('/{id}/cancel', [VaccinationController::class, 'cancel'])->name('cancel');
    });
    
    // Alias routes for vaccinations
    Route::get('/vaccinations/create', [VaccinationController::class, 'create'])->name('vaccinations.create');
    Route::get('/vaccinations/bookings', [VaccinationController::class, 'bookings'])->name('vaccinations.bookings.index');
    
    // Health Screening Routes (Authenticated)
    Route::prefix('health-screenings')->name('health-screenings.')->group(function () {
        Route::get('/', [HealthScreeningController::class, 'index'])->name('index');
        Route::get('/create', [HealthScreeningBookingController::class, 'create'])->name('booking.create');
        Route::post('/', [HealthScreeningBookingController::class, 'store'])->name('booking.store');
        Route::get('/bookings', [HealthScreeningBookingController::class, 'index'])->name('booking.index');
        Route::get('/booking/{id}', [HealthScreeningBookingController::class, 'show'])->name('booking.show');
        Route::get('/booking/{id}/edit', [HealthScreeningBookingController::class, 'edit'])->name('booking.edit');
        Route::put('/booking/{id}', [HealthScreeningBookingController::class, 'update'])->name('booking.update');
        Route::delete('/booking/{id}', [HealthScreeningBookingController::class, 'destroy'])->name('booking.destroy');
        Route::get('/book', [HealthScreeningBookingController::class, 'create'])->name('book');
        Route::get('/{id}/cancel', [HealthScreeningBookingController::class, 'cancel'])->name('cancel');
        Route::get('/{id}', [HealthScreeningController::class, 'show'])->name('show');
        Route::get('/edit', [HealthScreeningController::class, 'edit'])->name('edit');
        Route::delete('/{id}', [HealthScreeningBookingController::class, 'destroy'])->name('destroy');
    });
    
    // Alias route for health-screenings.create
    Route::get('/health-screenings/create', [HealthScreeningBookingController::class, 'create'])->name('health-screenings.create');
    
    // User-specific Routes
    Route::middleware(['role:user'])->group(function () {
        // User Appointments Routes
        Route::middleware(['auth', 'role:user'])->prefix('appointments')->name('appointments.')->group(function () {
            Route::get('/', [AppointmentController::class, 'index'])->name('index');
            Route::get('/create', [AppointmentController::class, 'create'])->name('create');
            Route::post('/', [AppointmentController::class, 'store'])->name('store');
            Route::get('/{id}', [AppointmentController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [AppointmentController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AppointmentController::class, 'update'])->name('update');
            Route::get('/{id}/cancel', [AppointmentController::class, 'cancel'])->name('cancel');
            Route::get('/{id}/verify', [AppointmentController::class, 'verify'])->name('verify');
            Route::post('/verify', [AppointmentController::class, 'verifyAppointment'])->name('verify.code');
        });
        
        // User-specific Vaccination Routes
        Route::prefix('user/vaccinations')->name('user.vaccination.')->group(function () {
            Route::get('/bookings', [VaccinationBookingController::class, 'userIndex'])->name('bookings.index');
            Route::get('/create', [VaccinationBookingController::class, 'userCreate'])->name('create');
            Route::post('/', [VaccinationBookingController::class, 'userStore'])->name('store');
            Route::get('/{id}', [VaccinationBookingController::class, 'userShow'])->name('show');
            Route::get('/{id}/cancel', [VaccinationBookingController::class, 'userCancel'])->name('cancel');
        });
        
        // User-specific Health Screening Routes
        Route::prefix('user/health-screenings')->name('user.health-screenings.')->group(function () {
            Route::get('/', [HealthScreeningBookingController::class, 'userIndex'])->name('index');
            Route::get('/create', [HealthScreeningBookingController::class, 'userCreate'])->name('create');
            Route::post('/', [HealthScreeningBookingController::class, 'userStore'])->name('store');
        });
        
        // User Messages Routes
        Route::prefix('user/messages')->name('user.messages.')->group(function () {
            Route::get('/', [UserMessageController::class, 'index'])->name('index');
            Route::get('/doctors', [UserMessageController::class, 'listDoctors'])->name('list_doctors');
            Route::get('/{doctorId}', [UserMessageController::class, 'show'])->name('show');
            Route::post('/store', [UserMessageController::class, 'store'])->name('store');
        });
        
        // User Pharmacy Routes
        Route::prefix('user/pharmacy')->name('user.pharmacy.')->group(function () {
            // Default route
            Route::get('/', function() {
                return redirect()->route('user.pharmacy.medications.index');
            })->name('index');

            // Medication Browsing
            Route::get('/medications', [UserPharmacyController::class, 'listMedications'])->name('medications.index');
            Route::get('/medications/search', [UserPharmacyController::class, 'searchMedications'])->name('medications.search');
            Route::get('/medications/{id}', [UserPharmacyController::class, 'showMedication'])->name('medications.show');
            
            // Prescription Management
            Route::get('/prescriptions', [UserPharmacyController::class, 'listPrescriptions'])->name('prescriptions.index');
            Route::get('/prescriptions/{id}', [UserPharmacyController::class, 'showPrescription'])->name('prescriptions.show');
            Route::post('/prescriptions/{id}/refill', [UserPharmacyController::class, 'requestPrescriptionRefill'])->name('prescriptions.refill');
            
            // Cart Management
            Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
            Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
            Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
            Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
            Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
        });
    });
    
    // Doctor Routes - All doctor routes consolidated here
    Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
        Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
        
        // Availability Management
        Route::prefix('availability')->name('availability.')->group(function () {
            Route::get('/', [DoctorDashboardController::class, 'availability'])->name('index');
            Route::post('/update', [DoctorDashboardController::class, 'updateAvailability'])->name('update');
        });
        
        // Patients
        Route::prefix('patients')->name('patients.')->group(function () {
            Route::get('/', [DoctorDashboardController::class, 'patients'])->name('index');
            Route::get('/create', [DoctorDashboardController::class, 'createPatient'])->name('create');
            Route::post('/', [DoctorDashboardController::class, 'storePatient'])->name('store');
            Route::get('/{id}', [DoctorDashboardController::class, 'showPatient'])->name('show');
            Route::get('/{id}/profile', [DoctorDashboardController::class, 'showPatientProfile'])->name('profile');
            Route::get('/{id}/edit', [DoctorDashboardController::class, 'editPatient'])->name('edit');
            Route::put('/{id}', [DoctorDashboardController::class, 'updatePatient'])->name('update');
        });
        
        // Prescriptions
        Route::prefix('prescriptions')->name('prescriptions.')->group(function () {
            Route::get('/', [DoctorPrescriptionController::class, 'index'])->name('index');
            Route::get('/create', [DoctorPrescriptionController::class, 'create'])->name('create');
            Route::post('/', [DoctorPrescriptionController::class, 'store'])->name('store');
            Route::get('/{id}', [DoctorPrescriptionController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [DoctorPrescriptionController::class, 'edit'])->name('edit');
            Route::put('/{id}', [DoctorPrescriptionController::class, 'update'])->name('update');
            Route::delete('/{id}', [DoctorPrescriptionController::class, 'destroy'])->name('destroy');
        });
        
        // Appointments
        Route::prefix('appointments')->name('appointments.')->group(function () {
            Route::get('/', [DoctorDashboardController::class, 'appointments'])->name('index');
            Route::get('/today', [DoctorDashboardController::class, 'todayAppointments'])->name('today');
            Route::get('/upcoming', [DoctorDashboardController::class, 'upcomingAppointments'])->name('upcoming');
            Route::get('/past', [DoctorDashboardController::class, 'pastAppointments'])->name('past');
            Route::get('/{id}', [DoctorDashboardController::class, 'showAppointment'])->name('show');
            Route::post('/{id}/accept', [DoctorDashboardController::class, 'acceptAppointment'])->name('accept');
            Route::post('/{id}/reject', [DoctorDashboardController::class, 'rejectAppointment'])->name('reject');
            Route::post('/{id}/complete', [DoctorDashboardController::class, 'completeAppointment'])->name('complete');
        });
        
        // Health Screenings
        Route::get('/health-screenings', [DoctorDashboardController::class, 'healthScreenings'])->name('health-screenings.index');
        
        // Screenings
        Route::prefix('screenings')->name('screenings.')->group(function () {
            Route::get('/', [DoctorDashboardController::class, 'healthScreenings'])->name('index');
            Route::get('/reports', [DoctorDashboardController::class, 'screeningReports'])->name('reports');
            Route::get('/{id}', [DoctorDashboardController::class, 'showScreening'])->name('show');
            Route::post('/{id}/approve', [DoctorDashboardController::class, 'approveScreening'])->name('approve');
            Route::post('/{id}/reject', [DoctorDashboardController::class, 'rejectScreening'])->name('reject');
        });
        
        // Vaccinations
        Route::prefix('vaccinations')->name('vaccinations.')->group(function () {
            Route::get('/', [DoctorDashboardController::class, 'vaccinations'])->name('index');
            Route::get('/{id}', [DoctorDashboardController::class, 'showVaccination'])->name('show');
            Route::post('/{id}/approve', [DoctorDashboardController::class, 'acceptVaccination'])->name('approve');
            Route::post('/{id}/reject', [DoctorDashboardController::class, 'declineVaccination'])->name('reject');
            Route::post('/{id}/complete', [DoctorDashboardController::class, 'finishVaccination'])->name('complete');
        });
        
        // Messages
        Route::prefix('messages')->name('messages.')->group(function () {
            Route::get('/', [\App\Http\Controllers\DoctorMessageController::class, 'index'])->name('index');
            Route::get('/conversation/{userId}', [\App\Http\Controllers\DoctorMessageController::class, 'conversation'])->name('conversation');
            Route::post('/send', [\App\Http\Controllers\DoctorMessageController::class, 'sendMessage'])->name('send');
            Route::get('/unread-count', [\App\Http\Controllers\DoctorMessageController::class, 'getUnreadCount'])->name('unread-count');
        });
        
        // Profile
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [DoctorProfileController::class, 'index'])->name('index');
            Route::get('/edit', [DoctorProfileController::class, 'edit'])->name('edit');
            Route::patch('/', [DoctorProfileController::class, 'update'])->name('update');
            Route::post('/change-password', [DoctorProfileController::class, 'changePassword'])->name('change-password');
        });
        
        // User Notifications Management
        Route::get('/user-notifications', [DoctorDashboardController::class, 'userNotifications'])->name('user-notifications');
        Route::post('/user-notifications/send', [DoctorDashboardController::class, 'sendUserNotification'])->name('user-notifications.send');
        
        // User Pharmacy Requests Management
        Route::get('/user-pharmacy', [DoctorDashboardController::class, 'userPharmacy'])->name('user-pharmacy');
        Route::get('/user-pharmacy/{id}', [DoctorDashboardController::class, 'showUserPharmacyRequest'])->name('user-pharmacy.show');
        Route::post('/user-pharmacy/{id}/approve', [DoctorDashboardController::class, 'approveUserPharmacyRequest'])->name('user-pharmacy.approve');
        Route::post('/user-pharmacy/{id}/reject', [DoctorDashboardController::class, 'rejectUserPharmacyRequest'])->name('user-pharmacy.reject');
    });
    
    // Global message routes (used in both patient and doctor interfaces)
    Route::post('/messages', [UserMessageController::class, 'store'])->name('messages.store');
    
    // Chat Routes
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/create/{doctor}', [ChatController::class, 'create'])->name('create');
        Route::post('/store', [ChatController::class, 'store'])->name('store');
        Route::get('/{thread}', [ChatController::class, 'show'])->name('show');
        Route::get('/messages/{doctor}', [ChatController::class, 'getMessages'])->name('messages');
        Route::post('/messages/{doctor}', [ChatController::class, 'sendMessage'])->name('send');
    });
    
    // Notification routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
    });
    
    // Payment Routes
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::post('/process', [PaymentController::class, 'process'])->name('process');
    });
    
    // Message Routes
    Route::prefix('messages')->name('messages.')->group(function () {
        // General Messages Route
        Route::get('/', [UserMessageController::class, 'index'])->name('index');
        
        // Doctor Messages Routes
        Route::prefix('doctors')->name('doctors.')->group(function () {
            Route::get('/', [DoctorMessageController::class, 'index'])->name('index');
            Route::get('/{doctorId}', [DoctorMessageController::class, 'show'])->name('show');
            Route::post('/store', [DoctorMessageController::class, 'store'])->name('store');
        });
        
        // User Messages Routes
        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/', [UserMessageController::class, 'index'])->name('index');
            Route::get('/doctors', [UserMessageController::class, 'listDoctors'])->name('list_doctors');
            Route::get('/{doctorId}', [UserMessageController::class, 'show'])->name('show');
            Route::post('/store', [UserMessageController::class, 'store'])->name('store');
        });
    });
    
    // Appointments Routes
    Route::prefix('appointments')->name('appointments.')->group(function () {
        Route::middleware(['auth'])->group(function () {
            Route::get('/', [AppointmentController::class, 'index'])->name('index');
            Route::get('/create', [AppointmentController::class, 'create'])->name('create');
            Route::post('/', [AppointmentController::class, 'store'])->name('store');
            Route::get('/{id}', [AppointmentController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [AppointmentController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AppointmentController::class, 'update'])->name('update');
            Route::patch('/{id}/cancel', [AppointmentController::class, 'cancel'])->name('cancel');
            Route::delete('/{id}', [AppointmentController::class, 'destroy'])->name('destroy');
        });
    });
});

// ==================== Admin Routes ====================
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Resource Routes
        Route::resource('users', UserController::class);
        Route::resource('doctors', AdminDoctorController::class);
        Route::resource('clinics', AdminClinicController::class);
        Route::resource('departments', AdminDepartmentController::class);
        Route::resource('health-screenings', AdminHealthScreeningsController::class);
        Route::resource('services', ServiceController::class);
        Route::resource('vaccinations', AdminVaccinationController::class);

        // Message Routes
        Route::get('/messages', [AdminMessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{id}', [AdminMessageController::class, 'show'])->name('messages.show');
        Route::patch('/messages/{id}', [AdminMessageController::class, 'updateStatus'])->name('messages.update');
        Route::delete('/messages/{id}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');
        Route::get('/messages/{id}/reply', [AdminMessageController::class, 'createReply'])->name('messages.reply');
        Route::post('/messages/{id}/reply', [AdminMessageController::class, 'storeReply'])->name('messages.reply.store');

        // Contact Messages Routes
        Route::prefix('contact-messages')->name('contact-messages.')->group(function () {
            Route::get('/', [AdminMessageController::class, 'contactMessagesIndex'])->name('index');
            Route::get('/{id}', [AdminMessageController::class, 'contactMessagesShow'])->name('show');
            Route::patch('/{id}/status', [AdminMessageController::class, 'updateContactMessageStatus'])->name('update-status');
            Route::delete('/{id}', [AdminMessageController::class, 'destroyContactMessage'])->name('destroy');
        });
        
        // Admin Profile Routes
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [AdminProfileController::class, 'index'])->name('show');
            Route::put('/', [AdminProfileController::class, 'update'])->name('update');
            Route::post('/change-password', [AdminProfileController::class, 'changePassword'])->name('change-password');
        });
        
        // Admin Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/users', [DashboardController::class, 'userReports'])->name('users');
            Route::get('/appointments', [DashboardController::class, 'appointmentReports'])->name('appointments');
            Route::get('/vaccinations', [DashboardController::class, 'vaccinationReports'])->name('vaccinations');
            Route::get('/health-screenings', [DashboardController::class, 'healthScreeningReports'])->name('health-screenings');
            Route::get('/doctors', [DashboardController::class, 'doctorReports'])->name('doctors');
        });
        
        // Admin Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [DashboardController::class, 'settings'])->name('index');
            Route::post('/', [DashboardController::class, 'updateSettings'])->name('update');
        });
    });
