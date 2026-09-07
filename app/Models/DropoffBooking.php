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

    // ── Queue/Antrian Helpers (computed, no schema change) ──

    /**
     * Get queue number for this booking (order of creation per scheduled_date).
     * Excludes cancelled bookings from the count.
     */
    public function getQueueNumberAttribute(): int
    {
        return self::where('scheduled_date', $this->scheduled_date)
            ->where('created_at', '<=', $this->created_at)
            ->whereIn('status', ['pending', 'verified', 'rejected'])
            ->count();
    }

    /**
     * Get total bookings for this booking's date (excludes cancelled).
     */
    public function getTotalQueueAttribute(): int
    {
        return self::where('scheduled_date', $this->scheduled_date)
            ->whereIn('status', ['pending', 'verified', 'rejected'])
            ->count();
    }

    /**
     * Get how many bookings have been processed (verified/rejected) for this date.
     */
    public function getCurrentServedAttribute(): int
    {
        return self::where('scheduled_date', $this->scheduled_date)
            ->whereIn('status', ['verified', 'rejected'])
            ->count();
    }

    /**
     * Static: count pending+active bookings for a given date.
     */
    public static function countForDate(string $date): int
    {
        return self::where('scheduled_date', $date)
            ->whereIn('status', ['pending', 'verified', 'rejected'])
            ->count();
    }
}
