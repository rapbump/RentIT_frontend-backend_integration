<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSellerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_and_non_admin_cannot_manage_sellers(): void
    {
        $this->get(route('admin.stores'))
            ->assertRedirect('/login');

        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('admin.stores'))
            ->assertForbidden();
    }

    public function test_admin_can_filter_sellers_and_update_verification_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pendingSeller = User::factory()->create([
            'name' => 'Pending Seller',
            'role' => 'lender',
            'store_name' => 'Pending Store',
            'seller_verification_submitted_at' => now(),
        ]);
        $approvedSeller = User::factory()->create([
            'name' => 'Approved Seller',
            'role' => 'lender',
            'store_name' => 'Approved Store',
        ]);
        $approvedSeller->forceFill(['seller_verification_status' => 'Approved'])->save();

        $this->actingAs($admin)
            ->get(route('admin.stores', ['status' => 'pending']))
            ->assertOk()
            ->assertSee('Pending Store')
            ->assertDontSee('Approved Store');

        $this->actingAs($admin)
            ->patch(route('admin.stores.verification', $pendingSeller), [
                'verification_status' => 'Approved',
            ])
            ->assertRedirect(route('admin.stores', ['status' => 'approved']))
            ->assertSessionHas('status', 'Seller verification status updated.');

        $this->assertDatabaseHas('users', [
            'id' => $pendingSeller->id,
            'seller_verification_status' => 'Approved',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.stores', ['status' => 'approved']))
            ->assertOk()
            ->assertSee('Pending Store')
            ->assertSee('Approved Store');
    }

    public function test_admin_cannot_set_an_unsupported_verification_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $seller = User::factory()->create(['role' => 'lender']);
        $seller->forceFill(['seller_verification_status' => 'Pending'])->save();

        $this->actingAs($admin)
            ->patch(route('admin.stores.verification', $seller), [
                'verification_status' => 'Suspended',
            ])
            ->assertSessionHasErrors('verification_status');

        $this->assertDatabaseHas('users', [
            'id' => $seller->id,
            'seller_verification_status' => 'Pending',
        ]);
    }

    public function test_admin_cannot_change_verification_status_of_a_non_lender(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($admin)
            ->patch(route('admin.stores.verification', $user), [
                'verification_status' => 'Approved',
            ])
            ->assertNotFound();
    }

    public function test_admin_cannot_approve_a_lender_who_has_not_submitted_verification_documents(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $seller = User::factory()->create([
            'role' => 'lender',
            'seller_verification_status' => 'Pending',
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.stores.verification', $seller), [
                'verification_status' => 'Approved',
            ])
            ->assertSessionHasErrors([
                'verification_status' => 'A seller must submit the required verification documents before approval.',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $seller->id,
            'seller_verification_status' => 'Pending',
        ]);
    }

    public function test_new_lender_registrations_start_in_pending_review(): void
    {
        $this->post(route('users'), [
            'name' => 'New Lender',
            'email' => 'new-lender@example.com',
            'phone' => '09123456789',
            'password' => 'StrongPass123!',
            'role' => 'lender',
            'terms' => '1',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', [
            'email' => 'new-lender@example.com',
            'role' => 'lender',
            'seller_verification_status' => 'Pending',
        ]);
    }

    public function test_public_registration_cannot_assign_the_admin_role(): void
    {
        $this->post(route('users'), [
            'name' => 'Unapproved Admin',
            'email' => 'unapproved-admin@example.com',
            'phone' => '09123456789',
            'password' => 'StrongPass123!',
            'role' => 'admin',
        ])->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('users', [
            'email' => 'unapproved-admin@example.com',
        ]);
    }
}
