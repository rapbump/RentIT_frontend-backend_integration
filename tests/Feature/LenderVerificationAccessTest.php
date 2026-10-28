<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LenderVerificationAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_lender_without_a_submitted_application_is_redirected_to_requirements_after_login(): void
    {
        $lender = User::factory()->create([
            'role' => 'lender',
            'seller_verification_status' => 'Pending',
        ]);

        $this->post(route('login'), [
            'email' => $lender->email,
            'password' => 'password',
        ])->assertRedirect(route('seller.verify'));

        $this->get(route('seller.verify'))
            ->assertOk()
            ->assertSee('Apply as a Verified Seller')
            ->assertSee('Government-Issued ID Upload')
            ->assertSee('Proof of Residence');
    }

    public function test_unapproved_lender_cannot_view_other_pages(): void
    {
        $lender = User::factory()->create([
            'role' => 'lender',
            'seller_verification_status' => 'Pending',
        ]);

        $this->actingAs($lender)
            ->get(route('homepage'))
            ->assertRedirect(route('seller.verify'));
    }

    public function test_lender_with_a_submitted_application_is_redirected_to_verification_status(): void
    {
        $lender = User::factory()->create([
            'role' => 'lender',
            'seller_verification_status' => 'Pending',
            'seller_verification_submitted_at' => now(),
        ]);

        $this->post(route('login'), [
            'email' => $lender->email,
            'password' => 'password',
        ])->assertRedirect(route('verification.message'));

        $this->get(route('verification.message'))
            ->assertOk()
            ->assertSee('Your lender account is awaiting verification')
            ->assertSee('Verification status: Pending');
    }

    public function test_rejected_lender_is_redirected_to_verification(): void
    {
        $lender = User::factory()->create([
            'role' => 'lender',
            'seller_verification_status' => 'Rejected',
            'seller_verification_submitted_at' => now(),
        ]);

        $this->actingAs($lender)
            ->get(route('lender.dashboard'))
            ->assertRedirect(route('verification.message'));

        $this->get(route('verification.message'))
            ->assertOk()
            ->assertSee('Your lender application needs attention')
            ->assertSee('Verification status: Rejected');
    }

    public function test_pending_lender_can_log_out(): void
    {
        $lender = User::factory()->create([
            'role' => 'lender',
            'seller_verification_status' => 'Pending',
        ]);

        $this->actingAs($lender)
            ->post(route('logout'))
            ->assertRedirect(route('homepage'));

        $this->assertGuest();
    }

    public function test_approved_lender_is_redirected_to_the_dashboard_from_verification(): void
    {
        $lender = User::factory()->approvedLender()->create();

        $this->actingAs($lender)
            ->get(route('verification.message'))
            ->assertRedirect(route('lender.dashboard'));
    }

    public function test_approved_lender_is_redirected_to_the_dashboard_after_login(): void
    {
        $lender = User::factory()->approvedLender()->create();

        $this->post(route('login'), [
            'email' => $lender->email,
            'password' => 'password',
        ])->assertRedirect(route('lender.dashboard'));
    }
}
