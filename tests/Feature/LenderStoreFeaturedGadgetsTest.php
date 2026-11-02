<?php

namespace Tests\Feature;

use App\Models\Gadget;
use App\Models\RentalTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LenderStoreFeaturedGadgetsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_the_lender_store(): void
    {
        $this->get(route('lender.store'))
            ->assertRedirect('/login');
    }

    public function test_non_lender_cannot_view_the_lender_store(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('lender.store'))
            ->assertForbidden();
    }

    public function test_store_shows_only_available_gadgets_for_its_lender(): void
    {
        $lender = User::factory()->approvedLender()->create();
        $otherLender = User::factory()->approvedLender()->create();

        Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Available Store Phone',
            'category' => 'Smartphone',
            'condition' => 'Excellent',
            'rental_payment' => 350,
            'status' => 'Available',
        ]);
        Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Draft Store Laptop',
            'category' => 'Laptop',
            'status' => 'Draft',
        ]);
        Gadget::create([
            'lender_id' => $otherLender->id,
            'name' => 'Other Lender Tablet',
            'category' => 'Tablet',
            'status' => 'Available',
        ]);

        $this->actingAs($lender)
            ->get(route('lender.store'))
            ->assertOk()
            ->assertSee('Available Store Phone')
            ->assertSee('₱350.00')
            ->assertDontSee('Draft Store Laptop')
            ->assertDontSee('Other Lender Tablet');
    }

    public function test_store_displays_no_more_than_four_available_gadgets(): void
    {
        $lender = User::factory()->approvedLender()->create();

        foreach (range(1, 5) as $number) {
            Gadget::create([
                'lender_id' => $lender->id,
                'name' => "Store Gadget {$number}",
                'category' => 'Smartphone',
                'status' => 'Available',
                'created_at' => now()->subDays(5 - $number),
            ]);
        }

        $this->actingAs($lender)
            ->get(route('lender.store'))
            ->assertOk()
            ->assertSee('Store Gadget 2')
            ->assertSee('Store Gadget 3')
            ->assertSee('Store Gadget 4')
            ->assertSee('Store Gadget 5')
            ->assertDontSee('Store Gadget 1');
    }

    public function test_store_shows_an_empty_state_when_no_gadgets_are_available(): void
    {
        $lender = User::factory()->approvedLender()->create();

        $this->actingAs($lender)
            ->get(route('lender.store'))
            ->assertOk()
            ->assertSee('No available gadgets yet')
            ->assertSee('Publish a gadget listing and it will appear here.');
    }

    public function test_public_store_is_available_without_sign_in_and_shows_only_available_gadgets(): void
    {
        $lender = User::factory()->approvedLender()->create([
            'store_name' => 'Public Camera Shop',
            'store_about' => 'A public store description.',
        ]);
        $otherLender = User::factory()->approvedLender()->create();
        Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Public Store Gadget',
            'category' => 'Camera',
            'status' => 'Available',
        ]);
        Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Draft Store Gadget',
            'category' => 'Laptop',
            'status' => 'Draft',
        ]);
        Gadget::create([
            'lender_id' => $otherLender->id,
            'name' => 'Another Store Gadget',
            'category' => 'Tablet',
            'status' => 'Available',
        ]);

        $this->get(route('lender.store.public', $lender))
            ->assertOk()
            ->assertSee('Public Camera Shop')
            ->assertSee('A public store description.')
            ->assertSee('Public Store Gadget')
            ->assertSee('href="'.route('customer.browse').'">', false)
            ->assertSee('Browse Gadgets')
            ->assertDontSee('Draft Store Gadget')
            ->assertDontSee('Another Store Gadget');
    }

    public function test_public_store_hides_gadgets_during_an_active_rental(): void
    {
        $this->travelTo('2026-11-02 12:00:00');
        $lender = User::factory()->approvedLender()->create();
        $renter = User::factory()->create(['role' => 'user']);
        $gadget = Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Public Store Currently Rented Gadget',
            'category' => 'Camera',
            'status' => 'Available',
        ]);
        RentalTransaction::create([
            'lender_id' => $lender->id,
            'renter_id' => $renter->id,
            'gadget_id' => $gadget->id,
            'start_date' => '2026-11-01',
            'end_date' => '2026-11-03',
            'daily_rate' => 100,
            'total_amount' => 200,
            'platform_fee' => 10,
            'status' => 'Active',
        ]);

        $this->get(route('lender.store.public', $lender))
            ->assertOk()
            ->assertDontSee('Public Store Currently Rented Gadget')
            ->assertSee('No available gadgets yet.');
    }

    public function test_lender_public_store_navigation_returns_to_the_lender_store(): void
    {
        $lender = User::factory()->approvedLender()->create();

        $this->actingAs($lender)
            ->get(route('lender.store.public', $lender))
            ->assertOk()
            ->assertSee('href="'.route('lender.store').'"', false)
            ->assertSee('My Store')
            ->assertDontSee('Browse Gadgets');
    }
}
