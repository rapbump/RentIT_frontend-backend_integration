<?php

namespace Tests\Feature;

use App\Models\Gadget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GadgetStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_or_submit_the_lender_gadget_form(): void
    {
        $this->get(route('lender.add-gadget'))
            ->assertRedirect('/login');

        $this->post(route('lender.add-gadget.store'))
            ->assertRedirect('/login');

        $this->assertDatabaseCount('gadgets', 0);
    }

    public function test_non_lender_cannot_save_a_gadget(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->post(route('lender.add-gadget.store'), [
                'action' => 'draft',
                'name' => 'Test Gadget',
                'category' => 'Smartphone',
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('gadgets', 0);
    }

    public function test_lender_can_publish_a_gadget_and_store_its_images(): void
    {
        Storage::fake('public');
        $lender = User::factory()->approvedLender()->create();
        $otherLender = User::factory()->approvedLender()->create();
        Gadget::create([
            'lender_id' => $otherLender->id,
            'name' => 'Another Lender Gadget',
            'category' => 'Camera',
        ]);

        $this->actingAs($lender)
            ->get(route('lender.add-gadget'))
            ->assertOk()
            ->assertSee('name="daily_rate"', false)
            ->assertSee('enctype="multipart/form-data"', false);

        $response = $this->post(route('lender.add-gadget.store'), [
            'action' => 'publish',
            'name' => 'Test Phone',
            'model' => 'Phone X',
            'category' => 'Smartphone',
            'brand' => 'Example',
            'condition' => 'Excellent',
            'description' => 'A test listing.',
            'photo' => UploadedFile::fake()->create('main.png', 100)->mimeType('image/png'),
            'additional_photos' => [
                UploadedFile::fake()->create('side.png', 100)->mimeType('image/png'),
                UploadedFile::fake()->create('back.png', 100)->mimeType('image/png'),
            ],
            'daily_rate' => '250.00',
            'weekly_rate' => '1500.00',
            'monthly_rate' => '5500.00',
            'security_deposit' => '5000.00',
            'rental_duration' => '1',
            'cancellation_policy' => 'Flexible',
            'pickup_mode' => 'Meetup',
            'delivery_fee' => '150.00',
            'availability_date' => '2026-10-18',
            'availability_end_date' => '2026-12-31',
        ]);

        $response->assertRedirect(route('lender.gadgets'));
        $response->assertSessionHas('success', 'Gadget published successfully.');

        $this->assertDatabaseHas('gadgets', [
            'lender_id' => $lender->id,
            'name' => 'Test Phone',
            'model' => 'Phone X',
            'category' => 'Smartphone',
            'brand' => 'Example',
            'condition' => 'Excellent',
            'description' => 'A test listing.',
            'rental_payment' => '250.00',
            'weekly_rate' => '1500.00',
            'monthly_rate' => '5500.00',
            'security_deposit' => '5000.00',
            'rental_duration' => 1,
            'cancellation_policy' => 'Flexible',
            'pickup_mode' => 'Meetup',
            'delivery_fee' => '150.00',
            'status' => 'Available',
        ]);

        $gadget = Gadget::where('name', 'Test Phone')->firstOrFail();
        Storage::disk('public')->assertExists($gadget->photo_path);
        $this->assertCount(2, $gadget->gallery_paths);
        $this->assertSame(['Meetup'], $gadget->meetup_options);
        $this->assertSame('2026-10-18', $gadget->availability_date->toDateString());
        $this->assertSame('2026-12-31', $gadget->availability_end_date->toDateString());
        foreach ($gadget->gallery_paths as $galleryPath) {
            Storage::disk('public')->assertExists($galleryPath);
        }

        $this->get(route('lender.gadgets'))
            ->assertOk()
            ->assertSee('Test Phone')
            ->assertSee('₱250.00 / day')
            ->assertDontSee('Another Lender Gadget');
    }

    public function test_lender_can_save_a_partial_gadget_draft(): void
    {
        $lender = User::factory()->approvedLender()->create();

        $response = $this->actingAs($lender)->post(route('lender.add-gadget.store'), [
            'action' => 'draft',
            'name' => 'Unfinished listing',
            'category' => 'Laptop',
        ]);

        $response->assertRedirect(route('lender.gadgets'));
        $response->assertSessionHas('success', 'Gadget draft saved.');
        $this->assertDatabaseHas('gadgets', [
            'lender_id' => $lender->id,
            'name' => 'Unfinished listing',
            'status' => 'Draft',
        ]);
    }

    public function test_lender_cannot_publish_a_gadget_when_availability_is_shorter_than_minimum_rental(): void
    {
        $lender = User::factory()->approvedLender()->create();

        $response = $this->actingAs($lender)
            ->from(route('lender.add-gadget'))
            ->post(route('lender.add-gadget.store'), [
                'action' => 'publish',
                'name' => 'Short Availability Gadget',
                'model' => 'Model One',
                'category' => 'Camera',
                'brand' => 'Example',
                'condition' => 'Good',
                'description' => 'A test listing.',
                'daily_rate' => '100.00',
                'security_deposit' => '500.00',
                'rental_duration' => '7',
                'cancellation_policy' => 'Flexible',
                'pickup_mode' => 'Meetup',
                'availability_date' => '2026-11-01',
                'availability_end_date' => '2026-11-07',
            ]);

        $response->assertRedirect(route('lender.add-gadget'))
            ->assertSessionHasErrors('availability_end_date');
        $this->assertSame(
            ['The availability period must include at least 7 days to meet the minimum rental duration.'],
            session('errors')->get('availability_end_date')
        );
        $this->assertDatabaseCount('gadgets', 0);
    }

    public function test_lender_can_edit_a_gadget_and_replace_its_main_image(): void
    {
        Storage::fake('public');
        $lender = User::factory()->approvedLender()->create();
        $oldPhoto = UploadedFile::fake()->create('old.png', 100, 'image/png')->store('gadgets', 'public');
        $galleryPhoto = UploadedFile::fake()->create('gallery.png', 100, 'image/png')->store('gadgets', 'public');
        $gadget = Gadget::create([
            'lender_id' => $lender->id,
            'name' => 'Original Listing',
            'model' => 'Model One',
            'category' => 'Camera',
            'brand' => 'Original Brand',
            'condition' => 'Good',
            'description' => 'Original description.',
            'photo_path' => $oldPhoto,
            'gallery_paths' => [$galleryPhoto],
            'rental_payment' => 100,
            'security_deposit' => 500,
            'status' => 'Rented',
        ]);

        $this->actingAs($lender)
            ->get(route('gadgets.edit', $gadget))
            ->assertOk()
            ->assertSee('value="Original Listing"', false)
            ->assertSee('value="Original Brand"', false)
            ->assertSee(route('gadgets.update', $gadget), false);

        $this->actingAs($lender)
            ->put(route('gadgets.update', $gadget), [
                'action' => 'publish',
                'name' => 'Updated Listing',
                'model' => 'Model Two',
                'category' => 'Laptop',
                'brand' => 'Updated Brand',
                'condition' => 'Excellent',
                'description' => 'Updated description.',
                'photo' => UploadedFile::fake()->create('new.png', 100, 'image/png'),
                'daily_rate' => '250.00',
                'weekly_rate' => '1500.00',
                'monthly_rate' => '5500.00',
                'security_deposit' => '750.00',
                'rental_duration' => '2',
                'cancellation_policy' => 'Moderate',
                'pickup_mode' => 'Meetup',
                'delivery_fee' => '25.00',
                'availability_date' => '2026-11-15',
                'availability_end_date' => '2026-12-31',
            ])
            ->assertRedirect(route('lender.gadgets'))
            ->assertSessionHas('success', 'Gadget updated successfully.');

        $gadget->refresh();

        $this->assertDatabaseHas('gadgets', [
            'id' => $gadget->id,
            'name' => 'Updated Listing',
            'model' => 'Model Two',
            'category' => 'Laptop',
            'brand' => 'Updated Brand',
            'condition' => 'Excellent',
            'description' => 'Updated description.',
            'rental_payment' => '250.00',
            'weekly_rate' => '1500.00',
            'monthly_rate' => '5500.00',
            'security_deposit' => '750.00',
            'status' => 'Rented',
        ]);
        Storage::disk('public')->assertMissing($oldPhoto);
        Storage::disk('public')->assertExists($gadget->photo_path);
        Storage::disk('public')->assertExists($galleryPhoto);
        $this->assertSame([$galleryPhoto], $gadget->gallery_paths);

        $this->actingAs($lender)
            ->get(route('lender.gadgets'))
            ->assertOk()
            ->assertSee('Updated Listing')
            ->assertSee('Edit');
    }

    public function test_lender_cannot_edit_another_lenders_gadget(): void
    {
        $lender = User::factory()->approvedLender()->create();
        $otherLender = User::factory()->approvedLender()->create();
        $gadget = Gadget::create([
            'lender_id' => $otherLender->id,
            'name' => 'Private Listing',
            'category' => 'Camera',
        ]);

        $this->actingAs($lender)
            ->get(route('gadgets.edit', $gadget))
            ->assertNotFound();

        $this->actingAs($lender)
            ->put(route('gadgets.update', $gadget), [])
            ->assertNotFound();

        $this->assertDatabaseHas('gadgets', [
            'id' => $gadget->id,
            'name' => 'Private Listing',
        ]);
    }

    public function test_invalid_published_listing_is_not_saved(): void
    {
        $lender = User::factory()->approvedLender()->create();

        $response = $this->actingAs($lender)->from(route('lender.add-gadget'))
            ->post(route('lender.add-gadget.store'), [
                'action' => 'publish',
                'name' => 'Incomplete listing',
                'category' => 'Smartphone',
            ]);

        $response->assertRedirect(route('lender.add-gadget'));
        $response->assertSessionHasErrors([
            'model',
            'brand',
            'condition',
            'description',
            'daily_rate',
            'security_deposit',
            'rental_duration',
            'cancellation_policy',
            'pickup_mode',
            'availability_date',
        ]);
        $this->assertDatabaseCount('gadgets', 0);
    }
}
