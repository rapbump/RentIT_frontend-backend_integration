<?php

namespace Tests\Feature;

use App\Models\Gadget;
use App\Models\PlatformSetting;
use App\Models\RentalTransaction;
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
        $lender = User::factory()->approvedLender()->create();
        $otherLender = User::factory()->approvedLender()->create();

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
        $renter = User::factory()->create(['role' => 'user']);
        $otherRenter = User::factory()->create(['role' => 'user']);
        $lenderGadget = Gadget::query()->where('name', 'Available This Week')->firstOrFail();
        $otherGadget = Gadget::query()->where('name', 'Other Lender Listing')->firstOrFail();
        RentalTransaction::create([
            'lender_id' => $lender->id,
            'renter_id' => $renter->id,
            'gadget_id' => $lenderGadget->id,
            'start_date' => '2026-11-01',
            'end_date' => '2026-11-03',
            'daily_rate' => 100,
            'total_amount' => 200,
            'platform_fee' => 0,
            'status' => 'Pending',
        ]);
        RentalTransaction::create([
            'lender_id' => $otherLender->id,
            'renter_id' => $otherRenter->id,
            'gadget_id' => $otherGadget->id,
            'start_date' => '2026-11-01',
            'end_date' => '2026-11-03',
            'daily_rate' => 100,
            'total_amount' => 200,
            'platform_fee' => 0,
            'status' => 'Pending',
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
            ->assertSee('data-stat="pending-bookings">1</p>', false)
            ->assertSee('href="'.route('lender.store.public', $lender).'"', false)
            ->assertSee('href="'.route('lender.dashboard.export').'"', false)
            ->assertSee('Requests waiting for your review')
            ->assertSee('Rental data is not available yet');
    }

    public function test_lender_can_download_a_csv_of_only_their_approved_rentals(): void
    {
        PlatformSetting::query()->findOrFail(1)->update(['platform_fee_percentage' => '5.00']);
        $lender = User::factory()->approvedLender()->create();
        $renter = User::factory()->create(['name' => 'CSV Renter']);
        $gadget = Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'CSV Camera',
            'category' => 'Camera',
        ]);
        $pendingGadget = Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Pending Gadget',
            'category' => 'Laptop',
        ]);
        $otherLender = User::factory()->approvedLender()->create();
        $otherGadget = Gadget::create([
            'lender_id' => $otherLender->id,
            'name' => 'Private Gadget',
            'category' => 'Tablet',
        ]);

        foreach ([
            [$gadget, $lender, 'Active'],
            [$pendingGadget, $lender, 'Pending'],
            [$otherGadget, $otherLender, 'Completed'],
        ] as [$rentalGadget, $rentalLender, $status]) {
            RentalTransaction::create([
                'lender_id' => $rentalLender->id,
                'renter_id' => $renter->id,
                'gadget_id' => $rentalGadget->id,
                'start_date' => '2026-11-01',
                'end_date' => '2026-11-03',
                'daily_rate' => 100,
                'total_amount' => 200,
                'platform_fee' => 10,
                'status' => $status,
            ]);
        }
        RentalTransaction::create([
            'lender_id' => $lender->id,
            'renter_id' => $renter->id,
            'gadget_id' => $gadget->id,
            'start_date' => '2026-10-01',
            'end_date' => '2026-10-03',
            'daily_rate' => 100,
            'total_amount' => 200,
            'platform_fee' => 10,
            'status' => 'Completed',
        ]);

        $this->actingAs($lender)
            ->get(route('lender.dashboard.export'))
            ->assertOk()
            ->assertStreamed()
            ->assertDownload('rentit-earnings-report.csv')
            ->assertStreamedContent(
                "\"Rental ID\",Gadget,Renter,\"Start Date\",\"End Date\",Status,\"Daily Rate\",\"Rental Total\",\"Platform Fee\",\"Estimated Payout\"\n"
                ."1,\"CSV Camera\",\"CSV Renter\",2026-11-01,2026-11-03,Active,100.00,200.00,10.00,190.00\n"
                ."4,\"CSV Camera\",\"CSV Renter\",2026-10-01,2026-10-03,Completed,100.00,200.00,10.00,190.00\n"
            );
    }

    public function test_lender_csv_uses_the_current_admin_platform_fee_percentage(): void
    {
        $lender = User::factory()->approvedLender()->create();
        $renter = User::factory()->create(['name' => 'Fee Update Renter']);
        $gadget = Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Fee Update Camera',
            'category' => 'Camera',
        ]);
        RentalTransaction::create([
            'lender_id' => $lender->id,
            'renter_id' => $renter->id,
            'gadget_id' => $gadget->id,
            'start_date' => '2026-11-01',
            'end_date' => '2026-11-03',
            'daily_rate' => 100,
            'total_amount' => 200,
            'platform_fee' => 10,
            'status' => 'Active',
        ]);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->put(route('admin.settings.platform'), ['platform_fee_percentage' => '12.50'])
            ->assertRedirect()
            ->assertSessionHas('status', 'Platform settings updated.');

        $this->actingAs($lender)
            ->get(route('lender.dashboard.export'))
            ->assertOk()
            ->assertStreamedContent(
                "\"Rental ID\",Gadget,Renter,\"Start Date\",\"End Date\",Status,\"Daily Rate\",\"Rental Total\",\"Platform Fee\",\"Estimated Payout\"\n"
                ."1,\"Fee Update Camera\",\"Fee Update Renter\",2026-11-01,2026-11-03,Active,100.00,200.00,25.00,175.00\n"
            );
    }
}
