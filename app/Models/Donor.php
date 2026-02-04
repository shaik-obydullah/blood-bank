<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Donor extends Authenticatable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'donors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fk_blood_group_id',
        'name',
        'country',
        'address_line_1',
        'address_line_2',
        'mobile',
        'last_donation_date',
        'email',
        'password',
        'birthdate',
        'hemoglobin_level',
        'systolic',
        'diastolic'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_donation_date' => 'date',
        'birthdate' => 'date',
        'hemoglobin_level' => 'decimal:2'
    ];

    /**
     * Get the blood group that owns the donor.
     */
    public function bloodGroup()
    {
        return $this->belongsTo(BloodGroup::class, 'fk_blood_group_id');
    }

    /**
     * Get the donor's full address.
     *
     * @return string
     */
    public function getFullAddressAttribute()
    {
        $address = '';

        if ($this->address_line_1) {
            $address .= $this->address_line_1;
        }

        if ($this->address_line_2) {
            $address .= $address ? ', ' . $this->address_line_2 : $this->address_line_2;
        }

        if ($this->country) {
            $address .= $address ? ', ' . $this->country : $this->country;
        }

        return $address;
    }

    /**
     * Get the donor's age.
     *
     * @return int
     */
    public function getAgeAttribute()
    {
        return $this->birthdate ? $this->birthdate->age : null;
    }

    /**
     * Get the donor's blood pressure as a formatted string.
     *
     * @return string|null
     */
    public function getBloodPressureAttribute()
    {
        if ($this->systolic && $this->diastolic) {
            return "{$this->systolic}/{$this->diastolic}";
        }

        if ($this->systolic) {
            return "{$this->systolic}/--";
        }

        if ($this->diastolic) {
            return "--/{$this->diastolic}";
        }

        return null;
    }

    /**
     * Check if donor is medically eligible based on hemoglobin and blood pressure.
     *
     * @return bool
     */
    public function isMedicallyEligible()
    {
        // Check hemoglobin levels (normal range: 12.5-17.5 g/dL)
        if ($this->hemoglobin_level && ($this->hemoglobin_level < 12.5 || $this->hemoglobin_level > 17.5)) {
            return false;
        }

        // Check blood pressure (normal range: systolic 90-180, diastolic 50-100)
        if ($this->systolic && ($this->systolic < 90 || $this->systolic > 180)) {
            return false;
        }

        if ($this->diastolic && ($this->diastolic < 50 || $this->diastolic > 100)) {
            return false;
        }

        return true;
    }

    /**
     * Check if donor is eligible for donation (if 3 months have passed since last donation)
     *
     * @return bool
     */
    public function isEligibleForDonation()
    {
        // Check age (must be at least 18)
        if ($this->age < 18) {
            return false;
        }

        // Check medical eligibility
        if (!$this->isMedicallyEligible()) {
            return false;
        }

        // Check donation interval
        if (!$this->last_donation_date) {
            return true;
        }

        $threeMonthsAgo = now()->subMonths(3);
        return $this->last_donation_date->lessThan($threeMonthsAgo);
    }

    /**
     * Get the donor's eligibility status.
     *
     * @return array
     */
    public function getEligibilityStatus()
    {
        $status = [
            'eligible' => true,
            'reasons' => []
        ];

        // Check age
        if ($this->age < 18) {
            $status['eligible'] = false;
            $status['reasons'][] = 'Donor must be at least 18 years old';
        }

        // Check hemoglobin
        if ($this->hemoglobin_level) {
            if ($this->hemoglobin_level < 12.5) {
                $status['eligible'] = false;
                $status['reasons'][] = 'Low hemoglobin level';
            } elseif ($this->hemoglobin_level > 17.5) {
                $status['eligible'] = false;
                $status['reasons'][] = 'High hemoglobin level';
            }
        }

        // Check blood pressure
        if ($this->systolic && ($this->systolic < 90 || $this->systolic > 180)) {
            $status['eligible'] = false;
            $status['reasons'][] = 'Blood pressure outside normal range';
        }

        if ($this->diastolic && ($this->diastolic < 50 || $this->diastolic > 100)) {
            $status['eligible'] = false;
            $status['reasons'][] = 'Blood pressure outside normal range';
        }

        // Check donation interval
        if ($this->last_donation_date) {
            $threeMonthsAgo = now()->subMonths(3);
            if (!$this->last_donation_date->lessThan($threeMonthsAgo)) {
                $status['eligible'] = false;
                $daysSinceDonation = now()->diffInDays($this->last_donation_date);
                $status['reasons'][] = "Last donation was {$daysSinceDonation} days ago (minimum 90 days required)";
            }
        }

        return $status;
    }

    /**
     * Scope a query to only include eligible donors.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEligible($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('last_donation_date')
                ->orWhere('last_donation_date', '<', now()->subMonths(3));
        })
            ->whereNotNull('birthdate')
            ->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) >= 18')
            ->where(function ($q) {
                $q->whereNull('hemoglobin_level')
                    ->orWhereBetween('hemoglobin_level', [12.5, 17.5]);
            })
            ->where(function ($q) {
                $q->whereNull('systolic')
                    ->orWhereBetween('systolic', [90, 180]);
            })
            ->where(function ($q) {
                $q->whereNull('diastolic')
                    ->orWhereBetween('diastolic', [50, 100]);
            });
    }

    /**
     * Scope a query to only include donors from a specific country.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $country
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFromCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    /**
     * Scope a query to search donors by name, mobile, or email.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('mobile', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%");
    }

    /**
     * Scope a query to filter donors by blood group.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $bloodGroupId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByBloodGroup($query, $bloodGroupId)
    {
        return $query->where('fk_blood_group_id', $bloodGroupId);
    }

    /**
     * Scope a query to filter donors by age range.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $minAge
     * @param  int  $maxAge
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAgeBetween($query, $minAge, $maxAge)
    {
        $minDate = now()->subYears($maxAge)->toDateString();
        $maxDate = now()->subYears($minAge)->toDateString();

        return $query->whereBetween('birthdate', [$minDate, $maxDate]);
    }
}