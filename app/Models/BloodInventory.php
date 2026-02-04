<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BloodInventory extends Model
{
    use HasFactory;

    protected $table = 'blood_inventory';

    protected $fillable = [
        'fk_blood_group_id',
        'fk_donor_id',
        'quantity',
        'collection_date',
        'expiry_date',
    ];

    /**
     * Get the blood group associated with the inventory.
     */
    public function bloodGroup(): BelongsTo
    {
        return $this->belongsTo(BloodGroup::class, 'fk_blood_group_id', 'id');
    }

    /**
     * Get the donor associated with the inventory.
     */
    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class, 'fk_donor_id');
    }
}