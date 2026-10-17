<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LenderSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_lender_settings(): void
    {
        $this->get(route('lender.settings'))
            ->assertRedirect('/login');
    }

    public function test_non_lender_cannot_view_or_update_lender_settings(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('lender.settings'))
            ->assertForbidden();

        $this->actingAs($user)
            ->put(route('lender.settings.profile'), [])
            ->assertForbidden();
    }

    public function test_settings_display_saved_name_and_destination(): void
    {
        $lender = User::factory()->create([
            'role' => 'lender',
            'name' => 'Casey Rivera',
            'location' => 'Malolos',
        ]);

        $this->actingAs($lender)
            ->get(route('lender.settings'))
            ->assertOk()
            ->assertSee('Casey Rivera')
            ->assertSee('value="Malolos"', false)
            ->assertSee('Display Name')
            ->assertSee('Destination');
    }

    public function test_lender_can_update_display_name_and_destination(): void
    {
        $lender = User::factory()->create([
            'role' => 'lender',
            'name' => 'Old Name',
            'location' => 'Old Destination',
            'email' => 'keep-this@example.test',
        ]);

        $this->actingAs($lender)
            ->put(route('lender.settings.profile'), [
                'name' => 'New Display Name',
                'location' => 'New Destination',
            ])
            ->assertRedirect()
            ->assertSessionHas('status', 'Name and destination saved.');

        $lender->refresh();
        $this->assertSame('New Display Name', $lender->name);
        $this->assertSame('New Destination', $lender->location);
        $this->assertSame('keep-this@example.test', $lender->email);
    }

    public function test_lender_can_change_password_with_the_current_password(): void
    {
        $lender = User::factory()->create([
            'role' => 'lender',
            'password' => 'current-password',
        ]);

        $this->actingAs($lender)
            ->put(route('lender.settings.password'), [
                'current_password' => 'current-password',
                'new_password' => 'a-secure-new-password',
                'new_password_confirmation' => 'a-secure-new-password',
            ])
            ->assertRedirect()
            ->assertSessionHas('status', 'Password updated successfully.');

        $this->assertTrue(Hash::check('a-secure-new-password', $lender->fresh()->password));
    }

    public function test_password_change_requires_the_correct_current_password(): void
    {
        $lender = User::factory()->create([
            'role' => 'lender',
            'password' => 'current-password',
        ]);

        $this->actingAs($lender)
            ->put(route('lender.settings.password'), [
                'current_password' => 'incorrect-password',
                'new_password' => 'a-secure-new-password',
                'new_password_confirmation' => 'a-secure-new-password',
            ])
            ->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('current-password', $lender->fresh()->password));
    }

    public function test_lender_can_sign_out_from_settings(): void
    {
        $lender = User::factory()->create(['role' => 'lender']);

        $this->actingAs($lender)
            ->post(route('logout'))
            ->assertRedirect(route('homepage'));

        $this->assertGuest();
    }
}
