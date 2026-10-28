<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_renter_can_register_with_valid_details_and_terms_acceptance(): void
    {
        $this->from(route('login.form'))
            ->followingRedirects()
            ->post(route('users'), [
                'name' => 'New Renter',
                'email' => 'new-renter@example.test',
                'phone' => '09123456789',
                'password' => 'StrongPass123!',
                'role' => 'user',
                'terms' => '1',
            ])
            ->assertOk()
            ->assertSee('Your account was created successfully. You can now log in.')
            ->assertSee('id="login-email"', false);

        $user = User::where('email', 'new-renter@example.test')->firstOrFail();

        $this->assertSame('New Renter', $user->name);
        $this->assertSame('user', $user->role);
        $this->assertTrue(Hash::check('StrongPass123!', $user->password));
    }

    public function test_registration_rejects_missing_details_and_unaccepted_terms(): void
    {
        $this->from(route('login.form'))
            ->post(route('users'), [])
            ->assertRedirect(route('login.form'))
            ->assertSessionHasErrors(['name', 'email', 'phone', 'password', 'role', 'terms']);

        $this->assertDatabaseCount('users', 0);
    }

    public function test_registration_rejects_an_email_that_is_already_in_use(): void
    {
        User::factory()->create(['email' => 'existing@example.test']);

        $this->from(route('login.form'))
            ->followingRedirects()
            ->post(route('users'), [
                'name' => 'Duplicate Email',
                'email' => 'existing@example.test',
                'phone' => '09123456789',
                'password' => 'StrongPass123!',
                'role' => 'user',
                'terms' => '1',
            ])
            ->assertOk()
            ->assertSee('Please correct the highlighted fields and try again.')
            ->assertSee('Duplicate Email')
            ->assertSee('has already been taken');

        $this->assertDatabaseCount('users', 1);
    }

    public function test_registration_rejects_invalid_email_phone_and_weak_password(): void
    {
        $this->post(route('users'), [
            'name' => 'Invalid Details',
            'email' => 'not-an-email',
            'phone' => 'phone-number',
            'password' => 'allletters',
            'role' => 'user',
            'terms' => '1',
        ])->assertSessionHasErrors(['email', 'phone', 'password']);

        $this->assertDatabaseCount('users', 0);
    }

    public function test_login_shows_a_notification_for_an_incorrect_password(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'email' => 'login-user@example.test',
            'password' => 'CorrectPass123!',
        ]);

        $this->from(route('login.form'))
            ->followingRedirects()
            ->post(route('login'), [
                'email' => $user->email,
                'password' => 'WrongPass123!',
            ])
            ->assertOk()
            ->assertSee('The email or password you entered is incorrect.')
            ->assertSee('role="alert"', false);

        $this->assertGuest();
    }
}
