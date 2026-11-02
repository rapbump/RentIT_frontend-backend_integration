<?php

namespace Tests\Feature;

use App\Models\Gadget;
use App\Models\RentalTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LenderRentersTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_and_non_lender_cannot_view_renters(): void
    {
        $this->get(route('lender.renters'))
            ->assertRedirect('/login');

        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('lender.renters'))
            ->assertForbidden();
    }

    public function test_renter_data_refreshes_from_transactions_and_opens_selected_email_message(): void
    {
        $lender = User::factory()->approvedLender()->create(['store_name' => 'Camera Store']);
        $renter = User::factory()->create([
            'name' => 'Jamie Renter',
            'email' => 'jamie@example.test',
            'location' => 'Malolos',
        ]);
        $gadget = Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Renter Camera',
            'category' => 'Camera',
        ]);

        $this->actingAs($lender)
            ->get(route('lender.renters'))
            ->assertOk()
            ->assertSee('Total Renters')
            ->assertSee('No renters found.');

        RentalTransaction::create([
            'lender_id' => $lender->id,
            'renter_id' => $renter->id,
            'gadget_id' => $gadget->id,
            'start_date' => '2026-10-01',
            'end_date' => '2026-10-03',
            'daily_rate' => 100,
            'total_amount' => 200,
            'platform_fee' => 10,
            'status' => 'Completed',
        ]);
        RentalTransaction::create([
            'lender_id' => $lender->id,
            'renter_id' => $renter->id,
            'gadget_id' => $gadget->id,
            'start_date' => '2026-11-01',
            'end_date' => '2026-11-03',
            'daily_rate' => 150,
            'total_amount' => 300,
            'platform_fee' => 15,
            'status' => 'Active',
        ]);

        $this->actingAs($lender)
            ->get(route('lender.renters', ['renter' => $renter->id]))
            ->assertOk()
            ->assertSee('Jamie Renter')
            ->assertSee('Malolos')
            ->assertSee('2 rentals')
            ->assertSee('₱500.00')
            ->assertSee('Renter Camera')
            ->assertSee('href="mailto:jamie@example.test?subject=Message%20from%20Camera%20Store"', false)
            ->assertSee('Currently Renting')
            ->assertSee('Returning Renters');
    }

    public function test_renter_search_and_status_filters_are_applied_without_changing_summary_counts(): void
    {
        $lender = User::factory()->approvedLender()->create();
        $activeRenter = User::factory()->create(['name' => 'Active Person']);
        $completedRenter = User::factory()->create(['name' => 'Completed Person']);
        $gadget = Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Filter Gadget',
            'category' => 'Camera',
        ]);

        foreach ([[$activeRenter, 'Active'], [$completedRenter, 'Completed']] as [$renter, $status]) {
            RentalTransaction::create([
                'lender_id' => $lender->id,
                'renter_id' => $renter->id,
                'gadget_id' => $gadget->id,
                'start_date' => '2026-11-01',
                'end_date' => '2026-11-03',
                'daily_rate' => 100,
                'total_amount' => 200,
                'platform_fee' => 10,
                'status' => $status,
            ]);
        }

        $this->actingAs($lender)
            ->get(route('lender.renters', ['search' => 'Completed', 'status' => 'completed']))
            ->assertOk()
            ->assertSee('Completed Person')
            ->assertDontSee('Active Person')
            ->assertSee('value="completed" selected', false)
            ->assertSee('>2</strong>', false);
    }

    public function test_lender_cannot_select_a_renter_from_another_lenders_transactions(): void
    {
        $lender = User::factory()->approvedLender()->create();
        $otherLender = User::factory()->approvedLender()->create();
        $renter = User::factory()->create(['name' => 'Other Lender Renter']);
        $gadget = Gadget::create([
            'lender_id' => $otherLender->id,
            'name' => 'Other Lender Gadget',
            'category' => 'Camera',
        ]);
        RentalTransaction::create([
            'lender_id' => $otherLender->id,
            'renter_id' => $renter->id,
            'gadget_id' => $gadget->id,
            'start_date' => '2026-11-01',
            'end_date' => '2026-11-03',
            'daily_rate' => 100,
            'total_amount' => 200,
            'platform_fee' => 10,
            'status' => 'Pending',
        ]);

        $this->actingAs($lender)
            ->get(route('lender.renters', ['renter' => $renter->id]))
            ->assertNotFound();
    }
}
