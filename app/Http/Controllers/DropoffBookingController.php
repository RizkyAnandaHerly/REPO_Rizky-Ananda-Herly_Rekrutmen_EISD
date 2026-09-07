<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\DropoffBooking;
use App\Models\WasteCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DropoffBookingController extends Controller
{
    /**
     * Display resident's own bookings (landing page after login).
     */
    public function index()
    {
        $user = Auth::user();

        $bookings = DropoffBooking::where('user_id', $user->id)
            ->latest()
            ->get();

        $pendingCount = $bookings->where('status', 'pending')->count();
        $totalPoints = $bookings->where('status', 'verified')->sum('total_points');

        return view('bookings.index', compact('bookings', 'pendingCount', 'totalPoints'));
    }

    /**
     * Show the booking creation form.
     */
    public function create()
    {
        $categories = WasteCategory::all();

        return view('bookings.create', compact('categories'));
    }

    /**
     * Store a new booking (Main Feature — uses StoreBookingRequest per Sequence Diagram).
     */
    public function store(StoreBookingRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();

        // Business rule: duplicate check — reject if pending booking exists for same date
        $duplicate = DropoffBooking::where('user_id', $user->id)
            ->where('scheduled_date', $validated['scheduled_date'])
            ->where('status', 'pending')
            ->exists();

        if ($duplicate) {
            return back()
                ->with('error', 'Anda sudah memiliki setoran pending untuk tanggal tersebut.')
                ->withInput();
        }

        // Generate unique booking code
        $bookingCode = 'RC-' . strtoupper(Str::random(8));
        while (DropoffBooking::where('booking_code', $bookingCode)->exists()) {
            $bookingCode = 'RC-' . strtoupper(Str::random(8));
        }

        // Create the booking
        $booking = DropoffBooking::create([
            'user_id' => $user->id,
            'booking_code' => $bookingCode,
            'scheduled_date' => $validated['scheduled_date'],
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        // Attach waste categories with estimated weights (LOOP from Sequence Diagram)
        foreach ($validated['waste_categories'] as $item) {
            $booking->wasteCategories()->attach($item['id'], [
                'estimated_weight' => $item['estimated_weight'],
            ]);
        }

        return redirect('/bookings')->with('success', 'Setoran berhasil diajukan.');
    }

    /**
     * Display booking detail (used by both resident and admin).
     */
    public function show(DropoffBooking $booking)
    {
        $user = Auth::user();

        // Resident can only see own bookings
        if ($user->isResident() && $booking->user_id !== $user->id) {
            abort(403);
        }

        $booking->load('wasteCategories', 'user');

        $view = $user->isAdmin() ? 'admin.bookings.show' : 'bookings.show';

        return view($view, compact('booking'));
    }

    /**
     * Cancel a pending booking (resident only, own booking only).
     */
    public function cancel(DropoffBooking $booking)
    {
        $user = Auth::user();

        // Only owner can cancel
        if ($booking->user_id !== $user->id) {
            abort(403);
        }

        // Only pending bookings can be cancelled
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Setoran tidak dapat dibatalkan.');
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Setoran berhasil dibatalkan.');
    }

    /**
     * Display all bookings for admin (landing page after admin login).
     */
    public function adminIndex()
    {
        $bookings = DropoffBooking::with('user')
            ->latest()
            ->get();

        $pendingCount = $bookings->where('status', 'pending')->count();

        return view('admin.bookings.index', compact('bookings', 'pendingCount'));
    }

    /**
     * Verify a booking — admin inputs verified_weight per category, calculates total points.
     */
    public function verify(Request $request, DropoffBooking $booking)
    {
        // Only pending bookings can be verified
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Setoran sudah diproses.');
        }

        $validated = $request->validate([
            'weights' => 'required|array',
            'weights.*' => 'required|numeric|min:0.1',
        ]);

        $totalPoints = 0;

        foreach ($booking->wasteCategories as $category) {
            $weight = $validated['weights'][$category->id] ?? null;
            if (!$weight) continue;

            $booking->wasteCategories()->updateExistingPivot($category->id, [
                'verified_weight' => $weight,
            ]);

            $totalPoints += $weight * $category->points_per_unit;
        }

        $booking->update([
            'status' => 'verified',
            'total_points' => (int) $totalPoints,
        ]);

        return back()->with('success', 'Setoran berhasil diverifikasi.');
    }

    /**
     * Reject a booking — admin sets status to rejected.
     */
    public function reject(DropoffBooking $booking)
    {
        // Only pending bookings can be rejected
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Setoran sudah diproses.');
        }

        $booking->update(['status' => 'rejected']);

        return back()->with('success', 'Setoran ditolak.');
    }
}
