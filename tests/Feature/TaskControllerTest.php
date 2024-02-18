<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;

class TaskControllerTest extends TestCase
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

     /** @test */
    public function Test_a_logged_in_user_can_create_a_new_task(): void
    {
        // Arrange: Create a user and authenticate
        $user = User::factory()->create();
       

        // Define the task data to send with the request
        $taskData = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => 'pending',
            'due_date' => now()->addWeek()->toDateString(), // Example date a week from now
        ];

        // Act: Make a POST request as the authenticated user
        $response = $this->actingAs($user)->post(route('tasks.store'), $taskData);

        // Assert: Check if the task was created
        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => 'Test Task',
        ]);

        // Check for a redirect to the 'tasks.list' route
        $response->assertRedirect(route('tasks.list'));

        // Check for a session message indicating success
        $response->assertSessionHas('success', 'Task created successfully.');
    }
    


    // Ddisplay a listing of tasks
    public function test_user_can_view_list_of_tasks()
    {
         //create user
         $user = User::factory()->create();
         //login
         $response = $this->post('login',[
             'email' => $user->email,
             'password' => 'password'
         ]);
 
         $this->assertAuthenticated();
         
         //create a task
         $response = $this->From(route('tasks.create'))->post(route('tasks.store'),[
             'title' => 'Test B236',
             'description'  => 'Test B237',
             'status' =>'pending',
             'due_date'  =>'2024-04-29 00:00:00',
         ]);

         // Act as the created user
        $response = $this->actingAs($user);

        // Act: Make a GET request to the index route
        $response = $this->get(route('tasks.list'));

        $response->assertStatus(200); // Assert that the response status is 200 OK

        $response->assertViewIs('tasks.list');
 
    }

    public function test_a_logged_in_user_can_update_a_task(): void
    {
        //create user
        $user = User::factory()->create();
        //login
        $response = $this->post('login',[
            'email' => $user->email,
            'password' => 'password'
        ]);

        $this->assertAuthenticated();
        
        //create a task
        $response = $this->From(route('tasks.create'))->post(route('tasks.store'),[
            'title' => 'Test B236',
            'description'  => 'Test B237',
            'status' =>'pending',
            'due_date'  =>'2024-04-29 00:00:00',
        ]);
        //check task created or not 
        $this->assertEquals(1,Task::count());


        //update the task
        $task = Task::first();
        $response = $this->From(route('tasks.edit',$task->id))->put(route('tasks.update',$task->id),[
            'title' => 'Test B23',
            'description'  => 'Test B237',
            'status' =>'completed',
            'due_date'  =>'2024-05-29 00:00:00',
        ]);
        
        //check updated value
        $update_task = Task::first();
        $this->assertEquals('Test B23',$update_task->title);
        $this->assertEquals('Test B237',$update_task->description);
        $this->assertEquals('completed',$update_task->status);
        $this->assertEquals('2024-05-29 00:00:00',$update_task->due_date);

        //check redirect 

        $response->assertStatus(302); // For redirect

        $response->assertRedirect(route('tasks.list'));
    }


    public function test_a_logged_in_user_and_role_admin_can_delete_a_task(): void
    {
        //create user as admin
        //$user = User::factory()->create();
        $user = User::factory()->role('Admin')->create();
        //login
        $response = $this->post('login',[
            'email' => $user->email,
            'password' => 'password'
        ]);

        $this->assertAuthenticated();
        
        //create a task
        $response = $this->From(route('tasks.create'))->post(route('tasks.store'),[
            'title' => 'Test B236',
            'description'  => 'Test B237',
            'status' =>'pending',
            'due_date'  =>'2024-04-29 00:00:00',
        ]);


      
        $task = Task::first();
        //delete the created task
        $response = $this->From(route('tasks.list'))->delete(route('tasks.destroy',$task->id));
        //check task deleted or not 
        $this->assertEquals(0,Task::count());
    }
}
