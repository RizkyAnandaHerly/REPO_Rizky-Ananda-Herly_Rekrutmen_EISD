<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\DropoffBooking;
use App\Models\User;
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
            ->paginate(5);

        $pendingCount = DropoffBooking::where('user_id', $user->id)->where('status', 'pending')->count();
        $totalPoints = DropoffBooking::where('user_id', $user->id)->where('status', 'verified')->sum('total_points');

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
     * Supports tab filtering via ?view=history query param and server-side search/filter.
     */
    public function adminIndex(Request $request)
    {
        $view = $request->query('view', 'pending');
        $search = $request->query('search');
        $status = $request->query('status');

        if ($view === 'history') {
            $query = DropoffBooking::with('user', 'wasteCategories')
                ->whereIn('status', ['verified', 'rejected', 'cancelled']);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('booking_code', 'like', "%{$search}%")
                        ->orWhereDate('scheduled_date', $search)
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('name', 'like', "%{$search}%");
                        });
                });
            }

            if ($status && in_array($status, ['verified', 'rejected', 'cancelled'])) {
                $query->where('status', $status);
            }

            $bookings = $query->latest('scheduled_date')
                ->paginate(5)
                ->withQueryString();
        } else {
            // Default: pending only, sorted by nearest date first
            $query = DropoffBooking::with('user', 'wasteCategories')
                ->where('status', 'pending');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('booking_code', 'like', "%{$search}%")
                        ->orWhereDate('scheduled_date', $search)
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('name', 'like', "%{$search}%");
                        });
                });
            }

            $bookings = $query->orderBy('scheduled_date', 'asc')
                ->paginate(5)
                ->withQueryString();
        }

        $pendingCount = DropoffBooking::where('status', 'pending')->count();
        $todayPendingCount = DropoffBooking::where('status', 'pending')
            ->whereDate('scheduled_date', today())
            ->count();

        return view('admin.bookings.index', compact('bookings', 'pendingCount', 'todayPendingCount', 'view', 'search', 'status'));
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

    /**
     * Display community eco-impact leaderboard (monthly, yearly, all-time, or archive).
     */
    public function leaderboard(Request $request)
    {
        $period = $request->query('period', 'current_month');
        $selectedMonth = $request->filled('month') ? (int) $request->query('month') : null;
        $selectedYear = $request->filled('year') ? (int) $request->query('year') : null;

        $now = now();

        if ($period === 'current_month') {
            $selectedMonth = (int) $now->month;
            $selectedYear = (int) $now->year;
        } elseif ($period === 'current_year') {
            $selectedMonth = null;
            $selectedYear = (int) $now->year;
        } elseif ($period === 'all_time') {
            $selectedMonth = null;
            $selectedYear = null;
        } elseif ($period === 'archive') {
            if (!$selectedMonth && !$selectedYear) {
                $lastMonth = $now->copy()->subMonth();
                $selectedMonth = (int) $lastMonth->month;
                $selectedYear = (int) $lastMonth->year;
            }
        }

        // Available periods for archive dropdown (from existing verified bookings)
        $availableDates = DropoffBooking::where('status', 'verified')
            ->pluck('scheduled_date')
            ->map(function ($date) {
                $c = \Carbon\Carbon::parse($date);
                return [
                    'year' => (int) $c->year,
                    'month' => (int) $c->month,
                    'label' => $c->locale('id')->translatedFormat('F Y'),
                ];
            })
            ->unique(function ($item) {
                return $item['year'] . '-' . $item['month'];
            })
            ->sortByDesc(function ($item) {
                return $item['year'] * 100 + $item['month'];
            })
            ->values();

        // Query active residents with verified bookings for the selected period
        $users = User::where('role', 'resident')
            ->withSum(['dropoffBookings' => function ($q) use ($selectedMonth, $selectedYear) {
                $q->where('status', 'verified');
                if ($selectedMonth) {
                    $q->whereMonth('scheduled_date', $selectedMonth);
                }
                if ($selectedYear) {
                    $q->whereYear('scheduled_date', $selectedYear);
                }
            }], 'total_points')
            ->withCount(['dropoffBookings' => function ($q) use ($selectedMonth, $selectedYear) {
                $q->where('status', 'verified');
                if ($selectedMonth) {
                    $q->whereMonth('scheduled_date', $selectedMonth);
                }
                if ($selectedYear) {
                    $q->whereYear('scheduled_date', $selectedYear);
                }
            }])
            ->get()
            ->map(function ($u) {
                $u->total_points = (int) ($u->dropoff_bookings_sum_total_points ?? 0);
                $u->deposits_count = (int) ($u->dropoff_bookings_count ?? 0);
                return $u;
            })
            ->filter(function ($u) {
                return $u->total_points > 0;
            })
            ->sortByDesc('total_points')
            ->values();

        // Assign ranks (1, 2, 3...)
        $rankedUsers = $users->map(function ($user, $index) {
            $user->rank = $index + 1;
            return $user;
        });

        $champion = $rankedUsers->first();
        $topThree = $rankedUsers->take(3);
        $remainingRanks = $rankedUsers->slice(3);

        // Find current logged in user's position
        $currentUser = Auth::user();
        $myRank = null;
        if ($currentUser && $currentUser->isResident()) {
            $myRank = $rankedUsers->firstWhere('id', $currentUser->id);
        }

        // Summary stats for selected period
        $totalCommunityPoints = $rankedUsers->sum('total_points');
        $totalCommunityDeposits = $rankedUsers->sum('deposits_count');

        // Period Label for Header display
        if ($period === 'current_month') {
            $periodTitle = 'Bulan Ini (' . $now->locale('id')->translatedFormat('F Y') . ')';
        } elseif ($period === 'current_year') {
            $periodTitle = 'Tahun Ini (' . $now->year . ')';
        } elseif ($period === 'all_time') {
            $periodTitle = 'Sepanjang Waktu (All-Time)';
        } else {
            $labelDate = \Carbon\Carbon::createFromDate($selectedYear ?? $now->year, $selectedMonth ?? $now->month, 1);
            $periodTitle = 'Arsip: ' . $labelDate->locale('id')->translatedFormat('F Y');
        }

        return view('leaderboard.index', compact(
            'rankedUsers',
            'topThree',
            'remainingRanks',
            'champion',
            'myRank',
            'period',
            'periodTitle',
            'selectedMonth',
            'selectedYear',
            'availableDates',
            'totalCommunityPoints',
            'totalCommunityDeposits'
        ));
    }
}
