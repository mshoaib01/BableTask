<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;                                                
    /**
     * A basic feature test example.
     */
    /*public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }*/

    
    public function test_showLoginForm(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_login_with_correct_credentials()
    {
        // Arrange
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
        ]);

        // Act
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        // Assert
        $response->assertRedirect(route('tasks.list')); // assuming you have a named route 'tasks.list'
        $this->assertAuthenticatedAs($user);
    }
}
