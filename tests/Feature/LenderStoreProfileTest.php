<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LenderStoreProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_lender_can_view_and_update_profile_and_store_information(): void
    {
        Storage::fake('public');

        $lender = User::factory()->approvedLender()->create([
            'name' => 'Original Lender',
            'email' => 'lender@example.test',
            'store_name' => 'Original Store',
        ]);

        $this->actingAs($lender)
            ->get(route('lender.store'))
            ->assertOk()
            ->assertSee('Original Store')
            ->assertSee('lender@example.test')
            ->assertSee('Edit Profile &amp; Store Info', false)
            ->assertSee(route('lender.store.profile'))
            ->assertSee('enctype="multipart/form-data"', false)
            ->assertSee('name="avatar"', false)
            ->assertSee('name="background"', false);

        $this->put(route('lender.store.profile'), [
            'name' => 'Updated Lender',
            'email' => 'lender@example.test',
            'phone' => '09123456789',
            'store_name' => 'Updated Store',
            'store_about' => 'Affordable devices for every occasion.',
            'location' => 'Baliuag, Bulacan',
            'operating_days' => 'Monday to Saturday',
            'business_hours' => '8:00 AM - 6:00 PM',
            'meetup_locations' => 'Baliuag town center',
            'avatar' => UploadedFile::fake()->create('avatar.png', 100, 'image/png'),
            'background' => UploadedFile::fake()->create('background.jpg', 100, 'image/jpeg'),
        ])->assertRedirect(route('lender.store'))
            ->assertSessionHas('status', 'Store profile and information updated.');

        $lender->refresh();

        $this->assertDatabaseHas('users', [
            'id' => $lender->id,
            'name' => 'Updated Lender',
            'email' => 'lender@example.test',
            'phone' => '09123456789',
            'store_name' => 'Updated Store',
            'store_about' => 'Affordable devices for every occasion.',
            'location' => 'Baliuag, Bulacan',
            'operating_days' => 'Monday to Saturday',
            'business_hours' => '8:00 AM - 6:00 PM',
            'meetup_locations' => 'Baliuag town center',
        ]);

        $this->assertNotNull($lender->avatar_path);
        $this->assertNotNull($lender->background_path);
        Storage::disk('public')->assertExists($lender->avatar_path);
        Storage::disk('public')->assertExists($lender->background_path);

        $this->get(route('lender.store'))
            ->assertOk()
            ->assertSee('Updated Store')
            ->assertSee('Affordable devices for every occasion.')
            ->assertSee('Baliuag, Bulacan')
            ->assertSee('09123456789')
            ->assertSee('Monday to Saturday')
            ->assertSee('Baliuag town center')
            ->assertSee(Storage::disk('public')->url($lender->avatar_path), false)
            ->assertSee(Storage::disk('public')->url($lender->background_path), false);
    }

    public function test_replacing_profile_images_removes_the_previous_uploads(): void
    {
        Storage::fake('public');
        $previousAvatar = UploadedFile::fake()->create('old-avatar.png', 100, 'image/png')->store('lender-profiles', 'public');
        $previousBackground = UploadedFile::fake()->create('old-background.png', 100, 'image/png')->store('lender-profiles', 'public');
        $lender = User::factory()->approvedLender()->create([
            'avatar_path' => $previousAvatar,
            'background_path' => $previousBackground,
        ]);

        $this->actingAs($lender)
            ->from(route('lender.store'))
            ->put(route('lender.store.profile'), [
                'name' => $lender->name,
                'email' => $lender->email,
                'avatar' => UploadedFile::fake()->create('new-avatar.png', 100, 'image/png'),
                'background' => UploadedFile::fake()->create('new-background.png', 100, 'image/png'),
            ])
            ->assertRedirect(route('lender.store'));

        $lender->refresh();

        Storage::disk('public')->assertMissing($previousAvatar);
        Storage::disk('public')->assertMissing($previousBackground);
        Storage::disk('public')->assertExists($lender->avatar_path);
        Storage::disk('public')->assertExists($lender->background_path);
    }

    public function test_lender_cannot_upload_a_non_image_as_a_store_photo(): void
    {
        Storage::fake('public');
        $lender = User::factory()->approvedLender()->create([
            'store_name' => 'Original Store',
        ]);

        $this->actingAs($lender)
            ->from(route('lender.store'))
            ->put(route('lender.store.profile'), [
                'name' => $lender->name,
                'email' => $lender->email,
                'store_name' => 'Changed Store',
                'avatar' => UploadedFile::fake()->create('not-an-image.txt', 100, 'text/plain'),
            ])
            ->assertRedirect(route('lender.store'))
            ->assertSessionHasErrors('avatar');

        $this->assertDatabaseHas('users', [
            'id' => $lender->id,
            'store_name' => 'Original Store',
            'avatar_path' => null,
        ]);

        Storage::disk('public')->assertDirectoryEmpty('lender-profiles');
    }

    public function test_lender_cannot_save_a_store_profile_with_an_email_owned_by_another_user(): void
    {
        $lender = User::factory()->approvedLender()->create([
            'email' => 'lender@example.test',
            'store_name' => 'Original Store',
        ]);
        User::factory()->create(['email' => 'another-user@example.test']);

        $this->actingAs($lender)
            ->from(route('lender.store'))
            ->put(route('lender.store.profile'), [
                'name' => 'Changed Lender',
                'email' => 'another-user@example.test',
                'store_name' => 'Changed Store',
            ])
            ->assertRedirect(route('lender.store'))
            ->assertSessionHasErrors('email');

        $this->assertDatabaseHas('users', [
            'id' => $lender->id,
            'name' => $lender->name,
            'email' => 'lender@example.test',
            'store_name' => 'Original Store',
        ]);
    }

    public function test_non_lender_cannot_update_store_profile(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->put(route('lender.store.profile'), [])
            ->assertForbidden();
    }
}
