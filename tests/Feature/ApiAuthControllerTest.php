<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ApiAuthControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function test_UserCanLoginWithCorrectCredentials(): void
    {
        //  Arrange: Create a user
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);

        // Act: Attempt to login
        $response = $this->postJson('/api/login', [
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        // Assert: Check for correct response status
        $response->assertStatus(200);

        // Assert: Check the structure of the response to include a token and user details
        $response->assertJsonStructure([
            'data' => [
                'token',
                'user' => [
                    'id',
                    'email',
                    // Add other fields as necessary
                ],
            ],
        ]);

        // Additional assertion to ensure a token is actually returned and not null or empty
        $this->assertNotNull($response['data']['token']);
        $this->assertIsString($response['data']['token']);
    }

       /**
     * Test login with incorrect credentials.
     *
     * @return void
     */
    public function testUserCannotLoginWithIncorrectCredentials()
    {
        // Arrange: Ensure no user exists
        // Act: Attempt to login with incorrect credentials
        $response = $this->postJson('/api/login', [
            'email' => 'wronguser@example.com',
            'password' => 'wrongpassword',
        ]);

        // Assert: Check for correct error message and status
        $response->assertStatus(401)
                 ->assertJson([
                     'message' => 'Incorrect credentials',
                 ]);
    }

    /**
     * Test login with missing fields.
     *
     * @return void
     */
    public function testUserCannotLoginWithMissingFields()
    {
        // Act: Attempt to login without providing email and password
        $response = $this->postJson('/api/login', []);

        // Assert: Check for validation errors and status
        $response->assertStatus(403)
                 ->assertJson([
                     'status' => 'failed',
                     'message' => 'Validation Error!',
                 ])
                 ->assertJsonStructure([
                     'data' => [
                         'email',
                         'password',
                     ],
                 ]);
    }
}
