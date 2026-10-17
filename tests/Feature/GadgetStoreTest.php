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
        $lender = User::factory()->create(['role' => 'lender']);
        $otherLender = User::factory()->create(['role' => 'lender']);
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
        $lender = User::factory()->create(['role' => 'lender']);

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

    public function test_invalid_published_listing_is_not_saved(): void
    {
        $lender = User::factory()->create(['role' => 'lender']);

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
