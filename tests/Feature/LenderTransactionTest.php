<?php

namespace Tests\Feature;

use App\Models\Gadget;
use App\Models\RentalTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LenderTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_the_lender_transactions(): void
    {
        $this->get(route('lender.transaction'))
            ->assertRedirect('/login');
    }

    public function test_non_lender_cannot_view_lender_transactions(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('lender.transaction'))
            ->assertForbidden();
    }

    public function test_lender_sees_only_their_active_rentals_and_monthly_totals(): void
    {
        $lender = User::factory()->approvedLender()->create();
        $otherLender = User::factory()->approvedLender()->create();
        $renter = User::factory()->create(['name' => 'October Renter']);
        $otherRenter = User::factory()->create(['name' => 'Other Lender Renter']);
        $gadget = Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'October Camera',
            'category' => 'Camera',
            'status' => 'Available',
        ]);
        $otherGadget = Gadget::create([
            'lender_id' => $otherLender->id,
            'name' => 'Private Tablet',
            'category' => 'Tablet',
            'status' => 'Available',
        ]);

        RentalTransaction::create([
            'lender_id' => $lender->id,
            'renter_id' => $renter->id,
            'gadget_id' => $gadget->id,
            'start_date' => '2026-10-10',
            'end_date' => '2026-10-13',
            'daily_rate' => 350,
            'total_amount' => 1050,
            'platform_fee' => 52.50,
            'status' => 'Active',
        ]);
        RentalTransaction::create([
            'lender_id' => $lender->id,
            'renter_id' => $renter->id,
            'gadget_id' => $gadget->id,
            'start_date' => '2026-10-02',
            'end_date' => '2026-10-04',
            'daily_rate' => 200,
            'total_amount' => 600,
            'platform_fee' => 30,
            'status' => 'Completed',
        ]);
        RentalTransaction::create([
            'lender_id' => $lender->id,
            'renter_id' => $renter->id,
            'gadget_id' => $gadget->id,
            'start_date' => '2026-10-05',
            'end_date' => '2026-10-06',
            'daily_rate' => 200,
            'total_amount' => 400,
            'platform_fee' => 20,
            'status' => 'Disputed',
        ]);
        RentalTransaction::create([
            'lender_id' => $otherLender->id,
            'renter_id' => $otherRenter->id,
            'gadget_id' => $otherGadget->id,
            'start_date' => '2026-10-10',
            'end_date' => '2026-10-13',
            'daily_rate' => 350,
            'total_amount' => 1050,
            'platform_fee' => 52.50,
            'status' => 'Active',
        ]);

        $this->actingAs($lender)
            ->get(route('lender.transaction', ['month' => '2026-10']))
            ->assertOk()
            ->assertSee('October Renter')
            ->assertSee('October Camera')
            ->assertSee('1,050.00')
            ->assertSee('997.50')
            ->assertSee('1 rental')
            ->assertSee('class="numbers-disputed">1</p>', false)
            ->assertDontSee('Other Lender Renter')
            ->assertDontSee('Private Tablet');
    }

    public function test_month_filter_includes_rentals_overlapping_the_selected_month_only(): void
    {
        $lender = User::factory()->approvedLender()->create();
        $renter = User::factory()->create();
        $gadget = Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Month Filter Gadget',
            'category' => 'Camera',
            'status' => 'Available',
        ]);

        RentalTransaction::create([
            'lender_id' => $lender->id,
            'renter_id' => $renter->id,
            'gadget_id' => $gadget->id,
            'start_date' => '2026-09-29',
            'end_date' => '2026-10-02',
            'daily_rate' => 100,
            'total_amount' => 400,
            'platform_fee' => 20,
            'status' => 'Active',
        ]);
        RentalTransaction::create([
            'lender_id' => $lender->id,
            'renter_id' => $renter->id,
            'gadget_id' => $gadget->id,
            'start_date' => '2026-09-01',
            'end_date' => '2026-09-02',
            'daily_rate' => 100,
            'total_amount' => 200,
            'platform_fee' => 10,
            'status' => 'Active',
        ]);
        RentalTransaction::create([
            'lender_id' => $lender->id,
            'renter_id' => $renter->id,
            'gadget_id' => $gadget->id,
            'start_date' => '2026-11-01',
            'end_date' => '2026-11-02',
            'daily_rate' => 100,
            'total_amount' => 200,
            'platform_fee' => 10,
            'status' => 'Active',
        ]);

        $this->actingAs($lender)
            ->get(route('lender.transaction', ['month' => '2026-10']))
            ->assertOk()
            ->assertSee('Month Filter Gadget')
            ->assertSee('400.00')
            ->assertDontSee('200.00')
            ->assertSee('value="2026-10"', false);
    }

    public function test_invalid_month_filter_is_rejected(): void
    {
        $lender = User::factory()->approvedLender()->create();

        $this->actingAs($lender)
            ->get(route('lender.transaction', ['month' => '2026-13']))
            ->assertSessionHasErrors('month');
    }

    public function test_empty_month_shows_database_derived_zero_totals_and_empty_state(): void
    {
        $lender = User::factory()->approvedLender()->create();

        $this->actingAs($lender)
            ->get(route('lender.transaction', ['month' => '2026-10']))
            ->assertOk()
            ->assertSee('No active rentals found for this month.')
            ->assertSee('0.00')
            ->assertSee('Completed Deals')
            ->assertSee('0 rentals');
    }
}
