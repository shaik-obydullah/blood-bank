<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodDistribution extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blood_distributions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fk_patient_id',
        'fk_blood_group_id',
        'request_unit',
        'approved_unit'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'request_unit' => 'integer',
        'approved_unit' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the patient that owns the blood distribution.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'fk_patient_id');
    }

    /**
     * Get the blood group that owns the blood distribution.
     */
    public function bloodGroup()
    {
        return $this->belongsTo(BloodGroup::class, 'fk_blood_group_id');
    }

    /**
     * Get the status of the distribution.
     *
     * @return string
     */
    public function getStatusAttribute(): string
    {
        if (is_null($this->approved_unit)) {
            return 'pending';
        } elseif ($this->approved_unit == 0) {
            return 'rejected';
        } elseif ($this->approved_unit < $this->request_unit) {
            return 'partially_approved';
        } elseif ($this->approved_unit == $this->request_unit) {
            return 'fully_approved';
        } else {
            return 'unknown';
        }
    }

    /**
     * Check if the distribution is pending approval.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return is_null($this->approved_unit);
    }

    /**
     * Check if the distribution is approved.
     *
     * @return bool
     */
    public function isApproved(): bool
    {
        return !is_null($this->approved_unit) && $this->approved_unit > 0;
    }

    /**
     * Check if the distribution is rejected.
     *
     * @return bool
     */
    public function isRejected(): bool
    {
        return !is_null($this->approved_unit) && $this->approved_unit == 0;
    }

    /**
     * Get the difference between requested and approved units.
     *
     * @return int
     */
    public function getDifferenceAttribute(): int
    {
        if (is_null($this->approved_unit)) {
            return $this->request_unit;
        }
        return $this->request_unit - $this->approved_unit;
    }

    /**
     * Get the approval percentage.
     *
     * @return float
     */
    public function getApprovalPercentageAttribute(): float
    {
        if (is_null($this->approved_unit) || $this->request_unit == 0) {
            return 0;
        }
        return ($this->approved_unit / $this->request_unit) * 100;
    }

    /**
     * Scope a query to only include pending distributions.
     */
    public function scopePending($query)
    {
        return $query->whereNull('approved_unit');
    }

    /**
     * Scope a query to only include approved distributions.
     */
    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_unit')
            ->where('approved_unit', '>', 0);
    }

    /**
     * Scope a query to only include rejected distributions.
     */
    public function scopeRejected($query)
    {
        return $query->whereNotNull('approved_unit')
            ->where('approved_unit', 0);
    }

    /**
     * Scope a query to only include distributions for a specific patient.
     */
    public function scopeForPatient($query, $patientId)
    {
        return $query->where('fk_patient_id', $patientId);
    }

    /**
     * Scope a query to only include distributions for a specific blood group.
     */
    public function scopeForBloodGroup($query, $bloodGroupId)
    {
        return $query->where('fk_blood_group_id', $bloodGroupId);
    }

    /**
     * Scope a query to only include distributions with minimum request amount.
     */
    public function scopeMinRequest($query, $amount)
    {
        return $query->where('request_unit', '>=', $amount);
    }

    /**
     * Get the display name for the distribution.
     *
     * @return string
     */
    public function getDisplayNameAttribute(): string
    {
        $patientName = $this->patient ? $this->patient->name : 'Unknown Patient';
        $bloodType = $this->bloodGroup ? $this->bloodGroup->code : 'Unknown';

        return "{$patientName} - {$bloodType} - {$this->request_unit}ML";
    }

    /**
     * Get the formatted created date.
     *
     * @return string
     */
    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at->format('M d, Y h:i A');
    }

    /**
     * Get the formatted updated date.
     *
     * @return string
     */
    public function getFormattedUpdatedAtAttribute(): string
    {
        return $this->updated_at->format('M d, Y h:i A');
    }

    /**
     * Approve the distribution.
     *
     * @param int $amount
     * @return bool
     */
    public function approve(int $amount): bool
    {
        if ($amount > $this->request_unit) {
            throw new \InvalidArgumentException('Approved amount cannot exceed requested amount');
        }

        $this->approved_unit = $amount;
        return $this->save();
    }

    /**
     * Reject the distribution.
     *
     * @return bool
     */
    public function reject(): bool
    {
        $this->approved_unit = 0;
        return $this->save();
    }

    /**
     * Get statistics for blood distributions.
     *
     * @return array
     */
    public static function getStatistics()
    {
        return [
            'total_requests' => self::count(),
            'pending_requests' => self::pending()->count(),
            'approved_requests' => self::approved()->count(),
            'rejected_requests' => self::rejected()->count(),
            'total_requested_ml' => self::sum('request_unit'),
            'total_approved_ml' => self::whereNotNull('approved_unit')->sum('approved_unit'),
        ];
    }
}