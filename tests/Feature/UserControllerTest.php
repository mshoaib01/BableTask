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

    public function test_user_login_with_email_password(): void
    {
        //create user
        //  Arrange: Create a user
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);
        //login
        $response = $this->post('/login', [ 
            'email' => $user->email,
            'password' => 'password'
        ]);

         

        // Assert: Check for correct response status
        $response->assertStatus(200);

        $response->assertAuthenticated();
    }
}
