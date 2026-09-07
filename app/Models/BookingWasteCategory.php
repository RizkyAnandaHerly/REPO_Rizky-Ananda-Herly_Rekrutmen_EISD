<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BookingWasteCategory extends Pivot
{
    protected $table = 'booking_waste_category';

    public $incrementing = true;

    protected $fillable = [
        'dropoff_booking_id',
        'waste_category_id',
        'estimated_weight',
        'verified_weight',
    ];

    protected function casts(): array
    {
        return [
            'estimated_weight' => 'decimal:2',
            'verified_weight' => 'decimal:2',
        ];
    }
}
