<?php

namespace Tests\Feature;

use App\Models\Gadget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerBrowseTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_browse_shows_available_gadgets_and_lender_data(): void
    {
        $lender = User::factory()->create([
            'name' => 'Browse Lender',
            'role' => 'lender',
        ]);
        $gadget = Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Camera <script>alert(1)</script>',
            'category' => 'Camera',
            'brand' => 'Canon',
            'model' => 'EOS 80D',
            'photo_path' => 'gadgets/camera.jpg',
            'rental_payment' => 350,
            'status' => 'Available',
        ]);
        Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Unpublished Laptop',
            'category' => 'Laptop',
            'status' => 'Draft',
        ]);

        $response = $this->get(route('customer.browse'));

        $response->assertOk()
            ->assertSee($gadget->name)
            ->assertSee('Browse Lender')
            ->assertSee('Canon EOS 80D')
            ->assertSee('₱350.00')
            ->assertSee('/storage/gadgets/camera.jpg')
            ->assertDontSee('Unpublished Laptop');
        $this->assertStringNotContainsString('<script>alert(1)</script>', $response->getContent());
    }

    public function test_browse_filters_available_gadgets_by_search_and_category(): void
    {
        $lender = User::factory()->create(['role' => 'lender']);
        Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'EOS Camera',
            'category' => 'Camera',
            'status' => 'Available',
        ]);
        Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Camera Phone',
            'category' => 'Smartphone',
            'status' => 'Available',
        ]);
        Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Draft EOS Camera',
            'category' => 'Camera',
            'status' => 'Draft',
        ]);

        $this->get(route('customer.browse', ['search' => 'EOS', 'category' => 'Camera']))
            ->assertOk()
            ->assertSee('EOS Camera')
            ->assertDontSee('Camera Phone')
            ->assertDontSee('Draft EOS Camera');
    }

    public function test_browse_shows_an_empty_state_when_no_gadgets_match(): void
    {
        $this->get(route('customer.browse'))
            ->assertOk()
            ->assertSee('No gadgets are available for rent right now.');

        $this->get(route('customer.browse', ['search' => 'no match']))
            ->assertOk()
            ->assertSee('No available gadgets match your search.');
    }

    public function test_browse_rejects_search_terms_longer_than_one_hundred_characters(): void
    {
        $this->get(route('customer.browse', ['search' => str_repeat('a', 101)]))
            ->assertSessionHasErrors('search');
    }
}
