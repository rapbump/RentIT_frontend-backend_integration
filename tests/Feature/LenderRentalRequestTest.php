<?php

namespace Tests\Feature;

use App\Models\Gadget;
use App\Models\RentalTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LenderRentalRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_lender_can_approve_their_pending_rental_request(): void
    {
        $lender = User::factory()->create(['role' => 'lender']);
        $renter = User::factory()->create(['role' => 'user', 'name' => 'Requesting Customer']);
        $gadget = $this->createGadget($lender);
        $rental = $this->createRequest($lender, $renter, $gadget);

        $this->actingAs($lender)
            ->get(route('lender.transaction', ['month' => '2026-11']))
            ->assertOk()
            ->assertSee('Pending Rental Requests')
            ->assertSee('Requesting Customer')
            ->assertSee('Approve')
            ->assertSee('Reject');

        $this->actingAs($lender)
            ->from(route('lender.transaction', ['month' => '2026-11']))
            ->patch(route('lender.transaction.approve', $rental))
            ->assertRedirect(route('lender.transaction', ['month' => '2026-11']))
            ->assertSessionHas('status', 'Rental request approved.');

        $this->assertDatabaseHas('rental_transactions', [
            'id' => $rental->id,
            'status' => 'Active',
        ]);
    }

    public function test_lender_can_reject_their_pending_rental_request(): void
    {
        $lender = User::factory()->create(['role' => 'lender']);
        $renter = User::factory()->create(['role' => 'user']);
        $gadget = $this->createGadget($lender);
        $rental = $this->createRequest($lender, $renter, $gadget);

        $this->actingAs($lender)
            ->patch(route('lender.transaction.reject', $rental))
            ->assertRedirect(route('lender.transaction', ['month' => '2026-11']))
            ->assertSessionHas('status', 'Rental request rejected.');

        $this->assertDatabaseHas('rental_transactions', [
            'id' => $rental->id,
            'status' => 'Rejected',
        ]);
    }

    public function test_lender_cannot_approve_another_lenders_request(): void
    {
        $lender = User::factory()->create(['role' => 'lender']);
        $otherLender = User::factory()->create(['role' => 'lender']);
        $renter = User::factory()->create(['role' => 'user']);
        $gadget = $this->createGadget($otherLender);
        $rental = $this->createRequest($otherLender, $renter, $gadget);

        $this->actingAs($lender)
            ->patch(route('lender.transaction.approve', $rental))
            ->assertNotFound();

        $this->assertModelExists($rental);
        $this->assertDatabaseHas('rental_transactions', [
            'id' => $rental->id,
            'status' => 'Pending',
        ]);
    }

    public function test_lender_cannot_approve_an_overlapping_active_rental(): void
    {
        $lender = User::factory()->create(['role' => 'lender']);
        $firstRenter = User::factory()->create(['role' => 'user']);
        $secondRenter = User::factory()->create(['role' => 'user']);
        $gadget = $this->createGadget($lender);
        $activeRental = $this->createRequest($lender, $firstRenter, $gadget, [
            'status' => 'Active',
            'start_date' => '2026-11-02',
            'end_date' => '2026-11-04',
        ]);
        $pendingRequest = $this->createRequest($lender, $secondRenter, $gadget);

        $this->actingAs($lender)
            ->from(route('lender.transaction', ['month' => '2026-11']))
            ->patch(route('lender.transaction.approve', $pendingRequest))
            ->assertRedirect(route('lender.transaction', ['month' => '2026-11']))
            ->assertSessionHasErrors('request');

        $this->assertDatabaseHas('rental_transactions', [
            'id' => $activeRental->id,
            'status' => 'Active',
        ]);
        $this->assertDatabaseHas('rental_transactions', [
            'id' => $pendingRequest->id,
            'status' => 'Pending',
        ]);
    }

    public function test_non_lender_cannot_approve_a_rental_request(): void
    {
        $customer = User::factory()->create(['role' => 'user']);
        $lender = User::factory()->create(['role' => 'lender']);
        $renter = User::factory()->create(['role' => 'user']);
        $gadget = $this->createGadget($lender);
        $rental = $this->createRequest($lender, $renter, $gadget);

        $this->actingAs($customer)
            ->patch(route('lender.transaction.approve', $rental))
            ->assertForbidden();

        $this->assertDatabaseHas('rental_transactions', [
            'id' => $rental->id,
            'status' => 'Pending',
        ]);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createGadget(User $lender, array $attributes = []): Gadget
    {
        return Gadget::create(array_merge([
            'lender_id' => $lender->id,
            'name' => 'Pending Request Gadget',
            'category' => 'Camera',
            'status' => 'Available',
        ], $attributes));
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createRequest(User $lender, User $renter, Gadget $gadget, array $attributes = []): RentalTransaction
    {
        return RentalTransaction::create(array_merge([
            'lender_id' => $lender->id,
            'renter_id' => $renter->id,
            'gadget_id' => $gadget->id,
            'start_date' => '2026-11-01',
            'end_date' => '2026-11-03',
            'daily_rate' => 100,
            'total_amount' => 200,
            'platform_fee' => 0,
            'status' => 'Pending',
        ], $attributes));
    }
}
