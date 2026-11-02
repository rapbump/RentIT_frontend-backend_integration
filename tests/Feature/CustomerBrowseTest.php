<?php

namespace Tests\Feature;

use App\Models\Gadget;
use App\Models\RentalTransaction;
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
            ->assertSee('href="'.route('details', $gadget).'"', false)
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

    public function test_browse_hides_gadgets_during_active_rentals_but_keeps_future_bookings_visible(): void
    {
        $this->travelTo('2026-11-02 12:00:00');
        $lender = User::factory()->approvedLender()->create();
        $currentRenter = User::factory()->create(['role' => 'user']);
        $futureRenter = User::factory()->create(['role' => 'user']);
        $currentlyRentedGadget = Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Currently Rented Camera',
            'category' => 'Camera',
            'status' => 'Available',
        ]);
        $futureRentedGadget = Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Future Booking Laptop',
            'category' => 'Laptop',
            'status' => 'Available',
        ]);

        foreach ([
            [$currentlyRentedGadget, $currentRenter, '2026-11-01', '2026-11-03'],
            [$futureRentedGadget, $futureRenter, '2026-11-10', '2026-11-12'],
        ] as [$gadget, $renter, $startDate, $endDate]) {
            RentalTransaction::create([
                'lender_id' => $lender->id,
                'renter_id' => $renter->id,
                'gadget_id' => $gadget->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'daily_rate' => 100,
                'total_amount' => 200,
                'platform_fee' => 10,
                'status' => 'Active',
            ]);
        }

        $this->get(route('customer.browse'))
            ->assertOk()
            ->assertDontSee('Currently Rented Camera')
            ->assertSee('Future Booking Laptop');
    }

    public function test_pending_rental_request_hides_gadget_from_customer_browse_details_and_cart(): void
    {
        $lender = User::factory()->approvedLender()->create();
        $customer = User::factory()->create(['role' => 'user']);
        $requestingCustomer = User::factory()->create(['role' => 'user']);
        $gadget = Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Pending Request Camera',
            'category' => 'Camera',
            'status' => 'Available',
        ]);
        RentalTransaction::create([
            'lender_id' => $lender->id,
            'renter_id' => $requestingCustomer->id,
            'gadget_id' => $gadget->id,
            'start_date' => '2026-11-10',
            'end_date' => '2026-11-12',
            'daily_rate' => 100,
            'total_amount' => 200,
            'platform_fee' => 10,
            'status' => 'Pending',
        ]);

        $this->get(route('customer.browse'))
            ->assertOk()
            ->assertDontSee('Pending Request Camera');

        $this->get(route('details', $gadget))
            ->assertNotFound();

        $this->actingAs($customer)
            ->post(route('customer.cart.store', $gadget))
            ->assertNotFound();
        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_browse_hides_gadgets_when_availability_is_shorter_than_minimum_rental(): void
    {
        $lender = User::factory()->approvedLender()->create();
        Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Insufficient Availability Camera',
            'category' => 'Camera',
            'rental_duration' => 7,
            'availability_date' => '2026-11-01',
            'availability_end_date' => '2026-11-07',
            'status' => 'Available',
        ]);
        Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Full Availability Laptop',
            'category' => 'Laptop',
            'rental_duration' => 7,
            'availability_date' => '2026-11-01',
            'availability_end_date' => '2026-11-08',
            'status' => 'Available',
        ]);

        $this->get(route('customer.browse'))
            ->assertOk()
            ->assertDontSee('Insufficient Availability Camera')
            ->assertSee('Full Availability Laptop');
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

    public function test_signed_in_customer_can_log_out_from_the_browse_page(): void
    {
        $customer = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($customer)
            ->get(route('customer.browse'));

        $response->assertOk()
            ->assertSee('<form action="'.route('logout').'" method="POST">', false)
            ->assertSee('aria-expanded="false"', false)
            ->assertSee('>Log Out</button>', false);

        $this->post(route('logout'))
            ->assertRedirect(route('homepage'));

        $this->assertGuest();
    }

    public function test_browse_rejects_search_terms_longer_than_one_hundred_characters(): void
    {
        $this->get(route('customer.browse', ['search' => str_repeat('a', 101)]))
            ->assertSessionHasErrors('search');
    }
}
