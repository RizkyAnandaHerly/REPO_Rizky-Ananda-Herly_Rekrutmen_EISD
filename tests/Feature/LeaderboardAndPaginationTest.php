<?php

namespace Tests\Feature;

use App\Models\DropoffBooking;
use App\Models\User;
use App\Models\WasteCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaderboardAndPaginationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $residentA;
    protected User $residentB;
    protected User $residentC;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Officer',
            'email' => 'admin_officer@resicycle.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
        $this->residentA = User::create([
            'name' => 'Warga Juara',
            'email' => 'warga_juara@resicycle.id',
            'password' => bcrypt('password'),
            'role' => 'resident',
        ]);
        $this->residentB = User::create([
            'name' => 'Warga Hebat',
            'email' => 'warga_hebat@resicycle.id',
            'password' => bcrypt('password'),
            'role' => 'resident',
        ]);
        $this->residentC = User::create([
            'name' => 'Warga Baru',
            'email' => 'warga_baru@resicycle.id',
            'password' => bcrypt('password'),
            'role' => 'resident',
        ]);
    }

    public function test_guest_cannot_access_leaderboard(): void
    {
        $response = $this->get(route('leaderboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_resident_and_admin_can_access_leaderboard(): void
    {
        $this->actingAs($this->residentA)
            ->get(route('leaderboard'))
            ->assertStatus(200)
            ->assertSee('Papan Peringkat Kontribusi');

        $this->actingAs($this->admin)
            ->get(route('leaderboard'))
            ->assertStatus(200)
            ->assertSee('Papan Peringkat Kontribusi');
    }

    public function test_leaderboard_correctly_ranks_residents_by_verified_points(): void
    {
        // residentA has 5000 points
        DropoffBooking::create([
            'user_id' => $this->residentA->id,
            'booking_code' => 'RC-TEST001',
            'scheduled_date' => now()->format('Y-m-d'),
            'status' => 'verified',
            'total_points' => 5000,
        ]);

        // residentB has 2500 points
        DropoffBooking::create([
            'user_id' => $this->residentB->id,
            'booking_code' => 'RC-TEST002',
            'scheduled_date' => now()->format('Y-m-d'),
            'status' => 'verified',
            'total_points' => 2500,
        ]);

        // residentC has pending booking with points 10000 (should NOT count because status is pending)
        DropoffBooking::create([
            'user_id' => $this->residentC->id,
            'booking_code' => 'RC-TEST003',
            'scheduled_date' => now()->format('Y-m-d'),
            'status' => 'pending',
            'total_points' => 10000,
        ]);

        $response = $this->actingAs($this->residentA)
            ->get(route('leaderboard', ['period' => 'current_month']));

        $response->assertStatus(200);
        $response->assertSee('Warga Juara');
        $response->assertSee('Warga Hebat');
        // residentA should be champion (Rank 1)
        $this->assertEquals('Warga Juara', $response->viewData('champion')->name);
        $this->assertEquals(5000, $response->viewData('champion')->total_points);
    }

    public function test_admin_bookings_history_paginates_5_items_per_page(): void
    {
        // Create 8 verified bookings
        for ($i = 1; $i <= 8; $i++) {
            DropoffBooking::create([
                'user_id' => $this->residentA->id,
                'booking_code' => "RC-PAGE{$i}",
                'scheduled_date' => now()->subDays($i)->format('Y-m-d'),
                'status' => 'verified',
                'total_points' => 100 * $i,
            ]);
        }

        $response = $this->actingAs($this->admin)
            ->get(route('admin.bookings.index', ['view' => 'history']));

        $response->assertStatus(200);
        $bookings = $response->viewData('bookings');

        $this->assertEquals(8, $bookings->total());
        $this->assertEquals(5, $bookings->perPage());
        $this->assertEquals(5, $bookings->count());
        $this->assertEquals(2, $bookings->lastPage());
    }

    public function test_admin_bookings_history_server_side_search_finds_item_from_another_page(): void
    {
        // Create 7 bookings
        for ($i = 1; $i <= 7; $i++) {
            DropoffBooking::create([
                'user_id' => $this->residentA->id,
                'booking_code' => "RC-NORM{$i}",
                'scheduled_date' => now()->subDays($i)->format('Y-m-d'),
                'status' => 'verified',
                'total_points' => 100,
            ]);
        }

        // Create 1 unique booking by residentB that would normally be on page 2
        DropoffBooking::create([
            'user_id' => $this->residentB->id,
            'booking_code' => 'RC-SPECIAL-CODE',
            'scheduled_date' => now()->subDays(10)->format('Y-m-d'),
            'status' => 'verified',
            'total_points' => 999,
        ]);

        // Search by booking code: should find it immediately
        $response = $this->actingAs($this->admin)
            ->get(route('admin.bookings.index', ['view' => 'history', 'search' => 'SPECIAL-CODE']));

        $response->assertStatus(200);
        $bookings = $response->viewData('bookings');
        $this->assertEquals(1, $bookings->total());
        $this->assertEquals('RC-SPECIAL-CODE', $bookings->first()->booking_code);

        // Search by user name: should find residentB
        $responseUser = $this->actingAs($this->admin)
            ->get(route('admin.bookings.index', ['view' => 'history', 'search' => 'Warga Hebat']));

        $responseUser->assertStatus(200);
        $this->assertEquals(1, $responseUser->viewData('bookings')->total());
    }
}
