<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WasteCategory extends Model
{
    protected $fillable = [
        'name',
        'unit',
        'points_per_unit',
        'description',
    ];

    // ── Relationships ──

    public function dropoffBookings()
    {
        return $this->belongsToMany(DropoffBooking::class, 'booking_waste_category')
                    ->withPivot('estimated_weight', 'verified_weight')
                    ->withTimestamps();
    }
}
