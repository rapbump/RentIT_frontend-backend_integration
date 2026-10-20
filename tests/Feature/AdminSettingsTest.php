<?php

namespace Tests\Feature;

use App\Models\PlatformSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_and_non_admin_cannot_access_admin_settings(): void
    {
        $this->get(route('admin.settings'))
            ->assertRedirect('/login');

        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('admin.settings'))
            ->assertForbidden();

        $this->actingAs($user)
            ->put(route('admin.settings.platform'), ['platform_fee_percentage' => 5])
            ->assertForbidden();
    }

    public function test_admin_settings_show_profile_and_saved_platform_defaults(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'name' => 'Admin Settings User',
            'email' => 'settings-admin@example.com',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.settings'))
            ->assertOk()
            ->assertSee('Admin Settings User')
            ->assertSee('settings-admin@example.com')
            ->assertSee('Default platform fee (%)')
            ->assertSee('value="0.00"', false);
    }

    public function test_admin_can_update_profile_and_platform_default(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'old-admin@example.com',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.settings.profile'), [
                'name' => 'Updated Admin',
                'email' => 'updated-admin@example.com',
            ])
            ->assertRedirect()
            ->assertSessionHas('status', 'Admin profile updated.');

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'name' => 'Updated Admin',
            'email' => 'updated-admin@example.com',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.settings.platform'), [
                'platform_fee_percentage' => '7.5',
            ])
            ->assertRedirect()
            ->assertSessionHas('status', 'Platform settings updated.');

        $this->assertSame('7.50', PlatformSetting::query()->findOrFail(1)->platform_fee_percentage);
    }

    public function test_admin_can_change_password_after_entering_the_current_password(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'password' => 'current-password',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.settings.password'), [
                'current_password' => 'current-password',
                'new_password' => 'a-secure-new-password',
                'new_password_confirmation' => 'a-secure-new-password',
            ])
            ->assertRedirect()
            ->assertSessionHas('status', 'Admin password updated.');

        $this->assertTrue(Hash::check('a-secure-new-password', $admin->fresh()->password));
    }

    public function test_invalid_profile_password_and_platform_settings_are_rejected(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->put(route('admin.settings.profile'), [
                'name' => 'Another Admin',
                'email' => $admin->email,
            ]);

        $otherAdmin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->put(route('admin.settings.profile'), [
                'name' => 'Another Admin',
                'email' => $otherAdmin->email,
            ])
            ->assertSessionHasErrors('email');

        $this->actingAs($admin)
            ->put(route('admin.settings.password'), [
                'current_password' => 'wrong-password',
                'new_password' => 'a-secure-new-password',
                'new_password_confirmation' => 'a-secure-new-password',
            ])
            ->assertSessionHasErrors('current_password');

        $this->actingAs($admin)
            ->put(route('admin.settings.platform'), [
                'platform_fee_percentage' => '100.01',
            ])
            ->assertSessionHasErrors('platform_fee_percentage');

        $this->assertSame('0.00', PlatformSetting::query()->findOrFail(1)->platform_fee_percentage);
    }
}
