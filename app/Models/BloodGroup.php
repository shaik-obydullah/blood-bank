<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BloodGroup extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blood_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the donors for the blood group.
     */
    public function donors(): HasMany
    {
        return $this->hasMany(Donor::class, 'fk_blood_group_id');
    }

    /**
     * Get the count of donors by blood group.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getDonorCounts()
    {
        return self::withCount('donors')
            ->orderBy('code')
            ->get();
    }

    /**
     * Get blood groups with positive Rh factor.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePositive($query)
    {
        return $query->where('code', 'like', '%+');
    }

    /**
     * Get blood groups with negative Rh factor.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNegative($query)
    {
        return $query->where('code', 'like', '%-');
    }

    /**
     * Get blood groups by blood type (A, B, AB, O).
     *
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('code', 'like', strtoupper($type) . '%');
    }

    /**
     * Get the display name with code.
     *
     * @return string
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->name} ({$this->code})";
    }

    /**
     * Get the donor count for this blood group.
     *
     * @return int
     */
    public function getDonorCountAttribute(): int
    {
        return $this->donors()->count();
    }

    /**
     * Check if the blood group can be deleted.
     *
     * @return bool
     */
    public function canBeDeleted(): bool
    {
        return $this->donor_count === 0;
    }
}