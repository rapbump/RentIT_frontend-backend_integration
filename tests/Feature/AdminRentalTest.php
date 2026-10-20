<?php

namespace Tests\Feature;

use App\Models\Gadget;
use App\Models\RentalTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRentalTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_and_non_admin_cannot_view_admin_rentals(): void
    {
        $this->get(route('admin.rentals'))
            ->assertRedirect('/login');

        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('admin.rentals'))
            ->assertForbidden();
    }

    public function test_admin_sees_all_rentals_and_can_filter_by_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $lender = User::factory()->create([
            'name' => 'Rental Lender',
            'role' => 'lender',
            'store_name' => 'Rental Store',
        ]);
        $renter = User::factory()->create(['name' => 'Rental Customer']);
        $gadget = Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Rental Gadget',
            'category' => 'Camera',
            'status' => 'Available',
        ]);

        $completedRenter = User::factory()->create(['name' => 'Completed Customer']);
        RentalTransaction::create([
            'lender_id' => $lender->id,
            'renter_id' => $renter->id,
            'gadget_id' => $gadget->id,
            'start_date' => '2026-10-20',
            'end_date' => '2026-10-22',
            'daily_rate' => 100,
            'total_amount' => 200,
            'platform_fee' => 10,
            'status' => 'Active',
        ]);
        RentalTransaction::create([
            'lender_id' => $lender->id,
            'renter_id' => $completedRenter->id,
            'gadget_id' => $gadget->id,
            'start_date' => '2026-10-10',
            'end_date' => '2026-10-12',
            'daily_rate' => 100,
            'total_amount' => 200,
            'platform_fee' => 10,
            'status' => 'Completed',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.rentals'))
            ->assertOk()
            ->assertSee('Rental Customer')
            ->assertSee('Completed Customer')
            ->assertSee('Rental Store')
            ->assertSee('Rental Gadget')
            ->assertSee('Active')
            ->assertSee('Completed')
            ->assertSee('₱200.00');

        $this->actingAs($admin)
            ->get(route('admin.rentals', ['status' => 'active']))
            ->assertOk()
            ->assertSee('Rental Customer')
            ->assertSee('Active')
            ->assertDontSee('Completed Customer');
    }

    public function test_admin_rentals_reject_an_unknown_status_filter(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.rentals', ['status' => 'cancelled']))
            ->assertSessionHasErrors('status');
    }
}
