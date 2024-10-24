<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        // Run the role and permission seeder
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    }

    /**
     * Test user registration.
     *
     * @return void
     */
    public function test_user_registration()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Nabil test ',
            'email' => 'Nabiltest@mail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => '🎉 Registration successful! Welcome  Nabil test!',
                'user' => [
                    'name' => 'Nabil test',
                    'email' => 'Nabiltest@mail.com',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'Nabiltest@mail.com',
        ]);
    }

    /**
     * Test user login.
     *
     * @return void
     */
    public function test_user_login()
    {
        // Create a user
        $user = User::create([
            'name' => 'Nabil test',
            'email' => 'Nabiltest@mail.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'Nabiltest@mail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => '🔐 Login successful! Welcome back, Nabil test!',
                'user' => [
                    'name' => 'Nabil test',
                ],
            ]);
    }

    /**
     * Test user logout.
     *
     * @return void
     */
    public function test_user_logout()
    {
        // Create a user and log them in to get a token
        $user = User::create([
            'name' => 'Nabil test',
            'email' => 'Nabiltest@mail.com',
            'password' => Hash::make('password'),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        // Make a logout request with the token
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'message' => '👋 Logout successful! Come back soon!',
            ]);
    }

    /**
     * Test login with incorrect credentials.
     *
     * @return void
     */
    public function test_login_with_incorrect_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'incorrect@example.com',
            'password' => 'incorrect12222',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
