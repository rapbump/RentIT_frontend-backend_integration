<?php

namespace Tests\Feature;

use App\Models\Gadget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CustomerCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_prompted_to_sign_in_before_adding_a_gadget(): void
    {
        $gadget = $this->createGadget();

        $this->get(route('details', $gadget))
            ->assertOk()
            ->assertSee('Sign in')
            ->assertSee('to add this gadget to your cart.')
            ->assertSee('href="'.route('login.form').'"', false);

        $this->post(route('customer.cart.store', $gadget))
            ->assertRedirect(route('login.form'));

        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_customer_can_add_a_gadget_and_view_it_in_their_cart(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $gadget = $this->createGadget(['rental_payment' => 350]);

        $this->actingAs($customer)
            ->get(route('details', $gadget))
            ->assertOk()
            ->assertSee('Add to Cart')
            ->assertSee('Cart (0)');

        $this->actingAs($customer)
            ->post(route('customer.cart.store', $gadget))
            ->assertRedirect(route('customer.cart'))
            ->assertSessionHas('status', 'Added to your cart.');

        $this->assertDatabaseHas('cart_items', [
            'user_id' => $customer->id,
            'gadget_id' => $gadget->id,
        ]);

        $this->actingAs($customer)
            ->get(route('customer.cart'))
            ->assertOk()
            ->assertSee('Test Gadget')
            ->assertSee('₱350.00 / day')
            ->assertSee('Requests remain pending until approved.');

        $this->actingAs($customer)
            ->get(route('details', $gadget))
            ->assertSee('View Cart (1)');
    }

    public function test_adding_the_same_gadget_twice_keeps_only_one_cart_item(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $gadget = $this->createGadget();

        $this->actingAs($customer)->post(route('customer.cart.store', $gadget));
        $this->actingAs($customer)
            ->post(route('customer.cart.store', $gadget))
            ->assertSessionHas('status', 'This gadget is already in your cart.');

        $this->assertDatabaseCount('cart_items', 1);
    }

    public function test_customer_cart_shows_only_their_own_items(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $otherCustomer = User::factory()->create(['role' => 'user']);
        $customerGadget = $this->createGadget(['name' => 'My Cart Gadget']);
        $otherGadget = $this->createGadget(['name' => 'Other Customer Gadget']);
        $customer->cartItems()->create(['gadget_id' => $customerGadget->id]);
        $otherCustomer->cartItems()->create(['gadget_id' => $otherGadget->id]);

        $this->actingAs($customer)
            ->get(route('customer.cart'))
            ->assertOk()
            ->assertSee('My Cart Gadget')
            ->assertDontSee('Other Customer Gadget');
    }

    #[DataProvider('nonCustomerRoles')]
    public function test_non_customer_roles_cannot_access_or_modify_the_cart(string $role): void
    {
        $account = User::factory()->create(['role' => $role]);
        $gadget = $this->createGadget();

        $this->actingAs($account)
            ->get(route('customer.cart'))
            ->assertForbidden();

        $this->actingAs($account)
            ->post(route('customer.cart.store', $gadget))
            ->assertForbidden();

        $this->assertDatabaseCount('cart_items', 0);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function nonCustomerRoles(): array
    {
        return [
            'lender' => ['lender'],
            'admin' => ['admin'],
        ];
    }

    public function test_customer_cannot_add_a_gadget_they_own(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $gadget = $this->createGadget(['lender_id' => $customer->id]);

        $this->actingAs($customer)
            ->post(route('customer.cart.store', $gadget))
            ->assertForbidden();

        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_customer_cannot_add_a_gadget_that_is_unavailable(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $gadget = $this->createGadget(['status' => 'Draft']);

        $this->actingAs($customer)
            ->post(route('customer.cart.store', $gadget))
            ->assertNotFound();

        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_cart_warns_when_a_saved_gadget_becomes_unavailable(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $gadget = $this->createGadget();
        $customer->cartItems()->create(['gadget_id' => $gadget->id]);
        $gadget->update(['status' => 'Rented']);

        $this->actingAs($customer)
            ->get(route('customer.cart'))
            ->assertOk()
            ->assertSee('This gadget is no longer available.');
    }

    public function test_customer_can_remove_a_gadget_from_their_cart(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $gadget = $this->createGadget();
        $cartItem = $customer->cartItems()->create(['gadget_id' => $gadget->id]);

        $this->actingAs($customer)
            ->delete(route('customer.cart.destroy', $cartItem))
            ->assertRedirect(route('customer.cart'))
            ->assertSessionHas('status', 'Removed from your cart.');

        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }

    public function test_customer_cannot_remove_another_customers_cart_item(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $otherCustomer = User::factory()->create(['role' => 'user']);
        $gadget = $this->createGadget();
        $otherCartItem = $otherCustomer->cartItems()->create(['gadget_id' => $gadget->id]);

        $this->actingAs($customer)
            ->delete(route('customer.cart.destroy', $otherCartItem))
            ->assertNotFound();

        $this->assertModelExists($otherCartItem);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createGadget(array $attributes = []): Gadget
    {
        $lenderId = $attributes['lender_id'] ?? User::factory()->create(['role' => 'lender'])->id;

        return Gadget::create(array_merge([
            'lender_id' => $lenderId,
            'name' => 'Test Gadget',
            'category' => 'Camera',
            'status' => 'Available',
        ], $attributes));
    }
}
