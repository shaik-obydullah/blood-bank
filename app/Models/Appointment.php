<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'appointments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fk_donor_id',
        'fk_doctor_id',
        'appointment_time',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'appointment_time' => 'datetime',
    ];

    /**
     * Get the donor that owns the appointment.
     */
    public function donor()
    {
        return $this->belongsTo(Donor::class, 'fk_donor_id');
    }

    /**
     * Get the doctor that owns the appointment.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'fk_doctor_id');
    }

    /**
     * Scope a query to only include upcoming appointments.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcoming($query)
    {
        return $query->where('appointment_time', '>=', now())
            ->whereIn('status', ['Pending', 'Confirmed']);
    }

    /**
     * Scope a query to only include past appointments.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePast($query)
    {
        return $query->where('appointment_time', '<', now())
            ->orWhere('status', 'Completed');
    }

    /**
     * Scope a query to only include pending appointments.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    /**
     * Scope a query to only include confirmed appointments.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'Confirmed');
    }

    /**
     * Check if the appointment is upcoming.
     *
     * @return bool
     */
    public function isUpcoming()
    {
        return $this->appointment_time >= now() &&
            in_array($this->status, ['Pending', 'Confirmed']);
    }

    /**
     * Check if the appointment is past.
     *
     * @return bool
     */
    public function isPast()
    {
        return $this->appointment_time < now() || $this->status === 'Completed';
    }

    /**
     * Check if the appointment can be cancelled.
     *
     * @return bool
     */
    public function canBeCancelled()
    {
        return $this->status === 'Pending' ||
            ($this->status === 'Confirmed' && $this->appointment_time > now()->addHours(24));
    }

    /**
     * Get the appointment status with color.
     *
     * @return array
     */
    public function getStatusInfo()
    {
        $statuses = [
            'Pending' => [
                'color' => '#ff9800',
                'bg_color' => '#fff3e0',
                'icon' => 'clock'
            ],
            'Confirmed' => [
                'color' => '#2196f3',
                'bg_color' => '#e3f2fd',
                'icon' => 'check-circle'
            ],
            'Cancelled' => [
                'color' => '#f44336',
                'bg_color' => '#ffebee',
                'icon' => 'times-circle'
            ],
            'Completed' => [
                'color' => '#4caf50',
                'bg_color' => '#f1f8e9',
                'icon' => 'check-double'
            ]
        ];

        return $statuses[$this->status] ?? $statuses['Pending'];
    }

    /**
     * Cancel the appointment.
     *
     * @return bool
     */
    public function cancel()
    {
        if (!$this->canBeCancelled()) {
            return false;
        }

        $this->status = 'Cancelled';
        return $this->save();
    }

    /**
     * Confirm the appointment.
     *
     * @return bool
     */
    public function confirm()
    {
        if ($this->status !== 'Pending') {
            return false;
        }

        $this->status = 'Confirmed';
        return $this->save();
    }

    /**
     * Complete the appointment.
     *
     * @return bool
     */
    public function complete()
    {
        if (!in_array($this->status, ['Pending', 'Confirmed'])) {
            return false;
        }

        $this->status = 'Completed';
        return $this->save();
    }

    /**
     * Get the formatted appointment time.
     *
     * @return string
     */
    public function getFormattedTimeAttribute()
    {
        return $this->appointment_time?->format('M d, Y h:i A') ?? 'Not Set';
    }

    /**
     * Get the time until appointment.
     *
     * @return string|null
     */
    public function getTimeUntilAttribute()
    {
        if (!$this->appointment_time || $this->isPast()) {
            return null;
        }

        $diff = $this->appointment_time->diff(now());

        if ($diff->days > 0) {
            return $diff->days . ' day' . ($diff->days > 1 ? 's' : '') . ' from now';
        } elseif ($diff->h > 0) {
            return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' from now';
        } else {
            return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' from now';
        }
    }

    // Add this method to your Appointment model
    /**
     * Scope a query to check if donor can book new appointment.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $donorId
     * @param  \Carbon\Carbon  $date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasAppointmentOnDate($query, $donorId, $date)
    {
        return $query->where('fk_donor_id', $donorId)
            ->whereDate('appointment_time', $date)
            ->whereIn('status', ['Pending', 'Confirmed']);
    }

    /**
     * Get the next available date for appointment.
     *
     * @return \Carbon\Carbon
     */
    public static function getNextAvailableDate($donorId)
    {
        $lastAppointment = self::where('fk_donor_id', $donorId)
            ->where('status', '!=', 'Cancelled')
            ->orderBy('appointment_time', 'desc')
            ->first();

        if (!$lastAppointment) {
            return now()->addDays(1);
        }

        return $lastAppointment->appointment_time->addDays(7);
    }
}