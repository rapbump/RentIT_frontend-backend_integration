<?php

namespace Tests\Feature;

use App\Models\Gadget;
use App\Models\PlatformSetting;
use App\Models\RentalTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerRentalRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->travelTo('2026-10-20 12:00:00');
    }

    public function test_customer_submits_all_cart_gadgets_as_pending_requests_without_payment(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $firstGadget = $this->createGadget(['name' => 'Camera', 'rental_payment' => 100]);
        $secondGadget = $this->createGadget(['name' => 'Tablet', 'rental_payment' => 250]);
        $customer->cartItems()->create(['gadget_id' => $firstGadget->id]);
        $customer->cartItems()->create(['gadget_id' => $secondGadget->id]);
        PlatformSetting::query()->findOrFail(1)->update(['platform_fee_percentage' => '7.50']);

        $this->actingAs($customer)
            ->post(route('customer.cart.request'), [
                'start_date' => '2026-11-01',
                'end_date' => '2026-11-04',
            ])
            ->assertRedirect(route('myrentals', ['status' => 'pending']))
            ->assertSessionHas('status', '2 rental request(s) submitted for lender approval. No payment was taken.');

        $this->assertDatabaseHas('rental_transactions', [
            'renter_id' => $customer->id,
            'gadget_id' => $firstGadget->id,
            'daily_rate' => '100.00',
            'total_amount' => '300.00',
            'platform_fee' => '22.50',
            'status' => 'Pending',
        ]);
        $this->assertDatabaseHas('rental_transactions', [
            'renter_id' => $customer->id,
            'gadget_id' => $secondGadget->id,
            'total_amount' => '750.00',
            'platform_fee' => '56.25',
            'status' => 'Pending',
        ]);
        $firstRequest = RentalTransaction::query()
            ->where('gadget_id', $firstGadget->id)
            ->firstOrFail();
        $this->assertSame('2026-11-01', $firstRequest->start_date->toDateString());
        $this->assertSame('2026-11-04', $firstRequest->end_date->toDateString());
        $this->assertDatabaseCount('cart_items', 0);

        $this->actingAs($customer)
            ->get(route('myrentals', ['status' => 'pending']))
            ->assertOk()
            ->assertSee('Camera')
            ->assertSee('Tablet')
            ->assertSee('Pending');
    }

    public function test_guest_cannot_submit_cart_rental_requests(): void
    {
        $this->post(route('customer.cart.request'), [
            'start_date' => '2026-11-01',
            'end_date' => '2026-11-03',
        ])->assertRedirect(route('login.form'));

        $this->assertDatabaseCount('rental_transactions', 0);
    }

    public function test_customer_cannot_submit_a_request_with_an_empty_cart(): void
    {
        $customer = User::factory()->create(['role' => 'user']);

        $this->actingAs($customer)
            ->from(route('customer.cart'))
            ->post(route('customer.cart.request'), [
                'start_date' => '2026-11-01',
                'end_date' => '2026-11-03',
            ])
            ->assertRedirect(route('customer.cart'))
            ->assertSessionHasErrors('cart');

        $this->assertDatabaseCount('rental_transactions', 0);
    }

    public function test_customer_must_submit_a_valid_future_date_range(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $gadget = $this->createGadget(['rental_payment' => 100]);
        $customer->cartItems()->create(['gadget_id' => $gadget->id]);

        $this->actingAs($customer)
            ->from(route('customer.cart'))
            ->post(route('customer.cart.request'), [
                'start_date' => '2026-11-01',
                'end_date' => '2026-11-01',
            ])
            ->assertRedirect(route('customer.cart'))
            ->assertSessionHasErrors('end_date');

        $this->assertDatabaseCount('rental_transactions', 0);
        $this->assertDatabaseCount('cart_items', 1);
    }

    public function test_rental_request_cannot_start_in_the_past(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $gadget = $this->createGadget(['rental_payment' => 100]);
        $customer->cartItems()->create(['gadget_id' => $gadget->id]);

        $this->actingAs($customer)
            ->from(route('customer.cart'))
            ->post(route('customer.cart.request'), [
                'start_date' => '2026-10-19',
                'end_date' => '2026-10-21',
            ])
            ->assertRedirect(route('customer.cart'))
            ->assertSessionHasErrors('start_date');

        $this->assertDatabaseCount('rental_transactions', 0);
        $this->assertDatabaseCount('cart_items', 1);
    }

    public function test_unavailable_or_unpriced_cart_item_prevents_all_requests(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $pricedGadget = $this->createGadget(['name' => 'Priced Gadget', 'rental_payment' => 100]);
        $unpricedGadget = $this->createGadget(['name' => 'Unpriced Gadget']);
        $customer->cartItems()->create(['gadget_id' => $pricedGadget->id]);
        $customer->cartItems()->create(['gadget_id' => $unpricedGadget->id]);

        $this->actingAs($customer)
            ->from(route('customer.cart'))
            ->post(route('customer.cart.request'), [
                'start_date' => '2026-11-01',
                'end_date' => '2026-11-03',
            ])
            ->assertRedirect(route('customer.cart'))
            ->assertSessionHasErrors('cart');

        $this->assertDatabaseCount('rental_transactions', 0);
        $this->assertDatabaseCount('cart_items', 2);
    }

    public function test_active_rental_conflict_prevents_partial_cart_submission(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $availableGadget = $this->createGadget(['rental_payment' => 100]);
        $conflictingGadget = $this->createGadget(['rental_payment' => 200]);
        $customer->cartItems()->create(['gadget_id' => $availableGadget->id]);
        $customer->cartItems()->create(['gadget_id' => $conflictingGadget->id]);
        RentalTransaction::create([
            'lender_id' => $conflictingGadget->lender_id,
            'renter_id' => User::factory()->create(['role' => 'user'])->id,
            'gadget_id' => $conflictingGadget->id,
            'start_date' => '2026-11-02',
            'end_date' => '2026-11-04',
            'daily_rate' => 200,
            'total_amount' => 400,
            'platform_fee' => 0,
            'status' => 'Active',
        ]);

        $this->actingAs($customer)
            ->from(route('customer.cart'))
            ->post(route('customer.cart.request'), [
                'start_date' => '2026-11-01',
                'end_date' => '2026-11-03',
            ])
            ->assertRedirect(route('customer.cart'))
            ->assertSessionHasErrors('start_date');

        $this->assertDatabaseCount('rental_transactions', 1);
        $this->assertDatabaseCount('cart_items', 2);
    }

    public function test_rental_period_must_meet_each_gadgets_minimum_duration(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $gadget = $this->createGadget([
            'rental_payment' => 100,
            'rental_duration' => 3,
        ]);
        $customer->cartItems()->create(['gadget_id' => $gadget->id]);

        $this->actingAs($customer)
            ->from(route('customer.cart'))
            ->post(route('customer.cart.request'), [
                'start_date' => '2026-11-01',
                'end_date' => '2026-11-03',
            ])
            ->assertRedirect(route('customer.cart'))
            ->assertSessionHasErrors('end_date');

        $this->assertDatabaseCount('rental_transactions', 0);
    }

    public function test_requested_dates_must_fit_the_gadgets_availability_window(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $gadget = $this->createGadget([
            'rental_payment' => 100,
            'availability_date' => '2026-11-02',
            'availability_end_date' => '2026-11-10',
        ]);
        $customer->cartItems()->create(['gadget_id' => $gadget->id]);

        $this->actingAs($customer)
            ->from(route('customer.cart'))
            ->post(route('customer.cart.request'), [
                'start_date' => '2026-11-01',
                'end_date' => '2026-11-03',
            ])
            ->assertRedirect(route('customer.cart'))
            ->assertSessionHasErrors('start_date');

        $this->assertDatabaseCount('rental_transactions', 0);
        $this->assertDatabaseCount('cart_items', 1);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createGadget(array $attributes = []): Gadget
    {
        $lender = User::factory()->create(['role' => 'lender']);

        return Gadget::create(array_merge([
            'lender_id' => $lender->id,
            'name' => 'Rental Request Gadget',
            'category' => 'Camera',
            'status' => 'Available',
        ], $attributes));
    }
}
