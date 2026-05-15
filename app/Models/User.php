<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\CustomResetPasswordNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'role',
        'is_admin',
        'last_seen',
        'avatar',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'is_admin' => 'boolean',
        'last_seen' => 'datetime',
    ];

    /**
     * Get the appointments for the user.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the vaccination bookings for the user.
     */
    public function vaccinationBookings()
    {
        return $this->hasMany(VaccinationBooking::class);
    }

    /**
     * Get the health screening bookings for the user.
     */
    public function healthScreeningBookings()
    {
        return $this->hasMany(HealthScreeningBooking::class);
    }

    /**
     * Check if the user is an admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->is_admin;
    }

    /**
     * Check if the user is a doctor
     *
     * @return bool
     */
    public function isDoctor()
    {
        return $this->role === 'doctor';
    }

    /**
     * Check if the user is a patient
     *
     * @return bool
     */
    public function isPatient()
    {
        return $this->role === 'patient';
    }

    /**
     * Check if the user has a specific role
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        if ($role === 'admin') {
            return $this->isAdmin();
        }
        return $this->role === $role;
    }

    /**
     * Get the doctor profile for the user.
     */
    public function doctorProfile()
    {
        return $this->hasOne(DoctorProfile::class);
    }

    /**
     * Get the doctor's department.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the doctor's clinic.
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the doctor's appointments.
     */
    public function doctorAppointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    /**
     * Get the doctor's schedules.
     */
    public function doctorSchedules()
    {
        return $this->hasMany(DoctorSchedule::class, 'doctor_id');
    }

    /**
     * Check if the doctor is available.
     */
    public function isAvailable()
    {
        return $this->doctorProfile?->is_available ?? false;
    }

    /**
     * Get the patient profile associated with the user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function patientProfile()
    {
        return $this->hasOne(PatientProfile::class);
    }

    /**
     * Get the user's recent activity
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function recentActivity()
    {
        return $this->hasMany(UserActivity::class)
            ->orderBy('created_at', 'desc')
            ->take(5);
    }

    /**
     * Get the user's activity
     */
    public function activities()
    {
        return $this->hasMany(UserActivity::class);
    }

    /**
     * Get the pharmacy associated with the user
     */
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacy_id');
    }

    /**
     * Check if the user is a pharmacy staff
     *
     * @return bool
     */
    public function isPharmacyStaff()
    {
        return $this->role === 'pharmacy';
    }

    /**
     * Get recent activity for the user
     *
     * @return array
     */
    public function getRecentActivity()
    {
        $activities = [];
        
        // Get user's appointments
        if ($this->appointments()->exists()) {
            foreach ($this->appointments()->latest()->take(3)->get() as $appointment) {
                $activities[] = [
                    'title' => "Appointment with {$appointment->getDoctorName()}",
                    'created_at' => $appointment->created_at,
                    'status' => $appointment->status === 'completed' ? 'success' : 
                               ($appointment->status === 'pending' ? 'warning' : 'primary')
                ];
            }
        }
        
        // Get user's prescription refills if they exist
        if (method_exists($this, 'prescriptionRefills') && $this->prescriptionRefills()->exists()) {
            foreach ($this->prescriptionRefills()->latest()->take(2)->get() as $refill) {
                $activities[] = [
                    'title' => 'Prescription Refill: ' . $refill->medication_details,
                    'created_at' => $refill->created_at,
                    'status' => $refill->status === 'completed' ? 'success' : 
                               ($refill->status === 'pending' ? 'warning' : 'primary')
                ];
            }
        }
        
        // Get user's health screenings if they exist
        if (method_exists($this, 'healthScreenings') && $this->healthScreenings()->exists()) {
            foreach ($this->healthScreenings()->latest()->take(2)->get() as $screening) {
                $activities[] = [
                    'title' => 'Health Screening: ' . $screening->screening_type,
                    'created_at' => $screening->created_at,
                    'status' => $screening->status === 'completed' ? 'success' : 
                               ($screening->status === 'pending' ? 'warning' : 'primary')
                ];
            }
        }
        
        // Sort activities by created_at date
        usort($activities, function($a, $b) {
            return $b['created_at']->timestamp - $a['created_at']->timestamp;
        });
        
        // Return the most recent 5 activities
        return array_slice($activities, 0, 5);
    }

    /**
     * Check if user is online
     * 
     * @return bool
     */
    public function isOnline()
    {
        return $this->last_seen && $this->last_seen->gt(now()->subMinutes(5));
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }

    /**
     * Get the prescriptions where the user is the doctor.
     */
    public function doctorPrescriptions()
    {
        return $this->hasMany(Prescription::class, 'doctor_id');
    }

    /**
     * Get the prescriptions where the user is the patient.
     */
    public function patientPrescriptions()
    {
        return $this->hasMany(Prescription::class, 'patient_id');
    }

    /**
     * Get all prescriptions for the user.
     * For doctors: returns prescriptions they've written
     * For patients: returns prescriptions prescribed to them
     */
    public function prescriptions()
    {
        return $this->role === 'doctor' 
            ? $this->doctorPrescriptions()
            : $this->patientPrescriptions();
    }

    /**
     * Get all stock adjustments made by the user.
     */
    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class);
    }
}