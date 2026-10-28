<?php

namespace Tests\Feature;

use App\Models\Gadget;
use App\Models\RentalTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_details_page_loads_the_selected_available_gadget_from_the_database(): void
    {
        $lender = User::factory()->create([
            'role' => 'lender',
            'name' => 'Details Lender',
            'store_name' => 'Details Store',
            'location' => 'Malolos',
            'meetup_locations' => 'City center',
        ]);
        $gadget = Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Database Camera',
            'category' => 'Camera',
            'brand' => 'Canon',
            'model' => 'EOS R6',
            'condition' => 'Very Good',
            'description' => 'A camera listing loaded from storage.',
            'photo_path' => 'gadgets/camera.jpg',
            'rental_payment' => 375.50,
            'security_deposit' => 1000,
            'status' => 'Available',
        ]);

        $this->get(route('details', $gadget))
            ->assertOk()
            ->assertSee('Database Camera')
            ->assertSee('A camera listing loaded from storage.')
            ->assertSee('Canon')
            ->assertSee('EOS R6')
            ->assertSee('₱375.50')
            ->assertSee('₱1,000.00')
            ->assertSee('Details Store')
            ->assertSee('Malolos')
            ->assertSee('City center')
            ->assertSee('/storage/gadgets/camera.jpg')
            ->assertDontSee('Book Rental')
            ->assertDontSee('Sony Alpha 7 IV');
    }

    public function test_details_page_hides_gadgets_that_are_not_available(): void
    {
        $lender = User::factory()->approvedLender()->create();
        $gadget = Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Draft Camera',
            'category' => 'Camera',
            'status' => 'Draft',
        ]);

        $this->get(route('details', $gadget))
            ->assertNotFound();
    }

    public function test_guest_is_redirected_from_my_rentals(): void
    {
        $this->get(route('myrentals'))
            ->assertRedirect('/login');
    }

    public function test_non_renter_cannot_access_my_rentals(): void
    {
        $lender = User::factory()->approvedLender()->create();

        $this->actingAs($lender)
            ->get(route('myrentals'))
            ->assertForbidden();
    }

    public function test_renter_sees_only_their_rentals_and_can_filter_by_status(): void
    {
        $renter = User::factory()->create([
            'role' => 'user',
            'name' => 'Rental Page Customer',
            'email' => 'rental-page@example.test',
            'phone' => '09123456789',
            'location' => 'Malolos',
        ]);
        $otherRenter = User::factory()->create(['role' => 'user']);
        $lender = User::factory()->create([
            'role' => 'lender',
            'name' => 'Rental Page Lender',
            'store_name' => 'Rental Page Store',
        ]);
        $gadget = Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Rented Device',
            'category' => 'Smartphone',
            'status' => 'Available',
        ]);
        $otherGadget = Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Private Customer Gadget',
            'category' => 'Tablet',
            'status' => 'Available',
        ]);
        $completedGadget = Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Completed Rental Device',
            'category' => 'Camera',
            'status' => 'Available',
        ]);

        $this->createRental($renter, $lender, $gadget, 'Active');
        $this->createRental($renter, $lender, $completedGadget, 'Completed');
        $this->createRental($otherRenter, $lender, $otherGadget, 'Active');

        $this->actingAs($renter)
            ->get(route('myrentals'))
            ->assertOk()
            ->assertSee('Rental Page Customer')
            ->assertSee('rental-page@example.test')
            ->assertSee('09123456789')
            ->assertSee('Malolos')
            ->assertSee('Rented Device')
            ->assertSee('Completed Rental Device')
            ->assertSee('Rental Page Store')
            ->assertSee('Rental total: ₱200.00')
            ->assertSee('rental-stats">Completed</p>', false)
            ->assertSee('numbers-rented">2</p>', false)
            ->assertDontSee('Private Customer Gadget');

        $this->actingAs($renter)
            ->get(route('myrentals', ['status' => 'active']))
            ->assertOk()
            ->assertSee('Rented Device')
            ->assertSee('Active')
            ->assertDontSee('Completed Rental Device');
    }

    public function test_my_rentals_rejects_an_unknown_status_filter(): void
    {
        $renter = User::factory()->create(['role' => 'user']);

        $this->actingAs($renter)
            ->get(route('myrentals', ['status' => 'cancelled']))
            ->assertSessionHasErrors('status');
    }

    private function createRental(User $renter, User $lender, Gadget $gadget, string $status): RentalTransaction
    {
        return RentalTransaction::create([
            'lender_id' => $lender->id,
            'renter_id' => $renter->id,
            'gadget_id' => $gadget->id,
            'start_date' => '2026-10-20',
            'end_date' => '2026-10-22',
            'daily_rate' => 100,
            'total_amount' => 200,
            'platform_fee' => 10,
            'status' => $status,
        ]);
    }
}
