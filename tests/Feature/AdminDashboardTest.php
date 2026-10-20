<?php

namespace Tests\Feature;

use App\Models\Gadget;
use App\Models\RentalTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_live_database_metrics_and_recent_activity(): void
    {
        $lender = User::factory()->create([
            'name' => 'Dashboard Lender',
            'role' => 'lender',
            'store_name' => 'Dashboard Store',
        ]);
        $renter = User::factory()->create([
            'name' => 'Dashboard Renter',
            'role' => 'user',
        ]);
        $admin = User::factory()->create(['role' => 'admin']);

        $gadget = Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Dashboard Camera',
            'category' => 'Camera',
            'status' => 'Available',
        ]);
        Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Draft Camera',
            'category' => 'Camera',
            'status' => 'Draft',
        ]);

        RentalTransaction::create([
            'lender_id' => $lender->id,
            'renter_id' => $renter->id,
            'gadget_id' => $gadget->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
            'daily_rate' => 100,
            'total_amount' => 100,
            'platform_fee' => 12.50,
            'status' => 'Active',
        ]);
        RentalTransaction::create([
            'lender_id' => $lender->id,
            'renter_id' => $renter->id,
            'gadget_id' => $gadget->id,
            'start_date' => now()->subDays(2)->toDateString(),
            'end_date' => now()->subDay()->toDateString(),
            'daily_rate' => 100,
            'total_amount' => 200,
            'platform_fee' => 7.50,
            'status' => 'Completed',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('data-stat="stores">1</p>', false)
            ->assertSee('data-stat="renters">1</p>', false)
            ->assertSee('data-stat="gadgets">2</p>', false)
            ->assertSee('data-stat="rentals">2</p>', false)
            ->assertSee('data-stat="active-rentals">1</p>', false)
            ->assertSee('data-stat="platform-fees">₱20.00</p>', false)
            ->assertSee('Dashboard Store')
            ->assertSee('Dashboard Renter')
            ->assertSee('Dashboard Camera')
            ->assertDontSee('Mateo Domingo')
            ->assertDontSee('48 Stores');
    }

    public function test_non_admin_cannot_access_the_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }
}
