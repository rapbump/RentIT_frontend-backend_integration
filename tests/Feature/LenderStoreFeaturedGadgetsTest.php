<?php

namespace Tests\Feature;

use App\Models\Gadget;
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
        $lender = User::factory()->create(['role' => 'lender']);
        $otherLender = User::factory()->create(['role' => 'lender']);

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
        $lender = User::factory()->create(['role' => 'lender']);

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
        $lender = User::factory()->create(['role' => 'lender']);

        $this->actingAs($lender)
            ->get(route('lender.store'))
            ->assertOk()
            ->assertSee('No available gadgets yet')
            ->assertSee('Publish a gadget listing and it will appear here.');
    }
}
