<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DropoffBookingController;
use App\Http\Controllers\WasteCategoryController;
use App\Models\DropoffBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ── Public Routes ──

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect('/admin/bookings')
            : redirect('/bookings');
    }
    return view('welcome');
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// AJAX: Queue count for a specific date (route closure — no controller method needed)
Route::get('/api/queue-count', function (Request $request) {
    $request->validate(['date' => 'required|date']);
    $count = DropoffBooking::countForDate($request->date);
    return response()->json(['count' => $count]);
})->middleware(['auth', 'role:resident']);

// ── Resident Routes ──

Route::middleware(['auth', 'role:resident'])->group(function () {
    Route::get('/bookings', [DropoffBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [DropoffBookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [DropoffBookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [DropoffBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/cancel', [DropoffBookingController::class, 'cancel'])->name('bookings.cancel');
});

// AJAX: Admin pending count for notification badge (route closure — no controller method)
Route::get('/api/admin/pending-count', function () {
    $pending = DropoffBooking::where('status', 'pending')->count();
    $today = DropoffBooking::where('status', 'pending')
        ->whereDate('scheduled_date', today())->count();
    return response()->json(['pending' => $pending, 'today' => $today]);
})->middleware(['auth', 'role:admin']);

// ── Admin Routes ──

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Bookings management
    Route::get('/bookings', [DropoffBookingController::class, 'adminIndex'])->name('admin.bookings.index');
    Route::get('/bookings/{booking}', [DropoffBookingController::class, 'show'])->name('admin.bookings.show');
    Route::patch('/bookings/{booking}/verify', [DropoffBookingController::class, 'verify'])->name('admin.bookings.verify');
    Route::patch('/bookings/{booking}/reject', [DropoffBookingController::class, 'reject'])->name('admin.bookings.reject');

    // Waste categories CRUD
    Route::get('/waste-categories', [WasteCategoryController::class, 'index'])->name('admin.waste-categories.index');
    Route::get('/waste-categories/create', [WasteCategoryController::class, 'create'])->name('admin.waste-categories.create');
    Route::post('/waste-categories', [WasteCategoryController::class, 'store'])->name('admin.waste-categories.store');
    Route::get('/waste-categories/{category}/edit', [WasteCategoryController::class, 'edit'])->name('admin.waste-categories.edit');
    Route::put('/waste-categories/{category}', [WasteCategoryController::class, 'update'])->name('admin.waste-categories.update');
    Route::delete('/waste-categories/{category}', [WasteCategoryController::class, 'destroy'])->name('admin.waste-categories.destroy');
});
