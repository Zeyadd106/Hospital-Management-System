<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'status'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i'
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public static function daysOfWeek()
    {
        return [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday'
        ];
    }

    public function validateTimes($start_time, $end_time)
    {
        $start = strtotime($start_time);
        $end = strtotime($end_time);

        if ($start >= $end) {
            throw new \Exception('End time must be after start time');
        }

        return true;
    }
}
