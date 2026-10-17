<?php

namespace Tests\Feature;

use App\Models\Gadget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LenderDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_the_lender_dashboard(): void
    {
        $this->get(route('lender.dashboard'))
            ->assertRedirect('/login');
    }

    public function test_non_lender_cannot_access_the_lender_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('lender.dashboard'))
            ->assertForbidden();
    }

    public function test_dashboard_shows_gadget_counts_and_recent_listings_for_the_current_lender(): void
    {
        $lender = User::factory()->create(['role' => 'lender']);
        $otherLender = User::factory()->create(['role' => 'lender']);

        Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Available This Week',
            'category' => 'Smartphone',
            'status' => 'Available',
            'created_at' => now()->startOfWeek()->addDay(),
        ]);
        Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Draft This Week',
            'category' => 'Laptop',
            'status' => 'Draft',
            'created_at' => now()->startOfWeek()->addDays(2),
        ]);
        $oldListing = Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Old Listing',
            'category' => 'Camera',
            'status' => 'Available',
        ]);
        $oldListing->forceFill([
            'created_at' => now()->startOfWeek()->subDay(),
            'updated_at' => now()->startOfWeek()->subDay(),
        ])->save();
        Gadget::create([
            'lender_id' => $otherLender->id,
            'name' => 'Other Lender Listing',
            'category' => 'Tablet',
            'status' => 'Available',
            'created_at' => now(),
        ]);

        $this->actingAs($lender)
            ->get(route('lender.dashboard'))
            ->assertOk()
            ->assertSee('data-stat="active-gadgets">2</p>', false)
            ->assertSee('2 listed this week')
            ->assertSee('Available This Week')
            ->assertSee('Draft This Week')
            ->assertSee('Old Listing')
            ->assertDontSee('Other Lender Listing')
            ->assertSee('Rental data is not available yet');
    }
}
