<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_homepage_without_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_user_can_register_account_and_redirect_to_storefront(): void
    {
        $response = $this->post('/register', [
            'name' => 'Nguyen Van A',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/customer');
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'user',
        ]);
    }

    public function test_admin_can_login_and_visit_admin_products_page(): void
    {
        User::factory()->create([
            'name' => 'Admin Bakery',
            'email' => 'admin@example.com',
            'password' => 'admin123',
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/admin');

        $this->get('/admin')->assertOk();
    }

    public function test_authenticated_user_can_delete_their_account(): void
    {
        $user = User::factory()->create([
            'name' => 'Delete Me',
            'email' => 'delete@example.com',
            'password' => 'secret123',
            'role' => 'user',
        ]);

        $this->actingAs($user)
            ->delete('/account/delete')
            ->assertRedirect('/login');

        $this->assertDatabaseMissing('users', [
            'email' => 'delete@example.com',
        ]);
    }

    public function test_authenticated_user_can_change_their_password(): void
    {
        $user = User::factory()->create([
            'name' => 'Password User',
            'email' => 'password-user@example.com',
            'password' => bcrypt('old-password'),
            'role' => 'user',
        ]);

        $this->actingAs($user)
            ->from('/customer')
            ->post('/account/change-password', [
                'current_password' => 'old-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ])
            ->assertRedirect('/customer');

        $user->refresh();

        $this->assertTrue(password_verify('new-password', $user->password));
        $this->assertFalse(password_verify('old-password', $user->password));
    }
}
