<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DropoffBooking extends Model
{
    protected $fillable = [
        'user_id',
        'booking_code',
        'scheduled_date',
        'status',
        'notes',
        'total_points',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'total_points' => 'integer',
        ];
    }

    // ── Relationships ──

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wasteCategories()
    {
        return $this->belongsToMany(WasteCategory::class, 'booking_waste_category')
                    ->withPivot('estimated_weight', 'verified_weight')
                    ->withTimestamps();
    }

    // ── Helpers ──

    public function calculateTotalPoints(): int
    {
        $total = 0;

        foreach ($this->wasteCategories as $category) {
            if ($category->pivot->verified_weight !== null) {
                $total += $category->pivot->verified_weight * $category->points_per_unit;
            }
        }

        return (int) $total;
    }
}
