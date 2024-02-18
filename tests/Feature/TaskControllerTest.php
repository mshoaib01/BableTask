<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendEmailToUsersNewTaskCreatedJob;
use App\Jobs\SendEmailToUsersTaskUpdatedJob;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;


     /** @test */
    public function Test_a_logged_in_user_can_create_a_new_task(): void
    {
        Queue::fake(); // Fake job dispatching

        // Arrange
        $user = User::factory()->create();
        $this->actingAs($user);

        $taskData = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => 'pending',
            'due_date' => now()->addWeek()->toDateString(), // Use a dynamic date to avoid failures in the future
        ];

        // Act
        $response = $this->post(route('tasks.store'), $taskData);

        // Assert
        $response->assertRedirect(route('tasks.list'));
        $response->assertSessionHas('success', 'Task created successfully.');
        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'user_id' => $user->id, // Ensure the task is assigned to the authenticated user
        ]);

        // Assert that the job was dispatched
        Queue::assertPushed(SendEmailToUsersNewTaskCreatedJob::class);
    }
    


    // Ddisplay a listing of tasks
     /** @test */
    public function test_user_can_view_list_of_tasks()
    {
          // Arrange: Create some tasks in the database
        $tasks = Task::factory()->count(5)->create();

        // Act: Visit the tasks index route
        $response = $this->get('/tasks/list');

        // Assert: Check if the correct view is returned with the tasks
        $response->assertStatus(200);
        $response->assertViewIs('tasks.list');
 
    }

     /** @test */
    public function test_a_logged_in_user_can_update_a_task(): void
    {
        Queue::fake();

        // Arrange: Create a user and task
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'title' => 'Old Title',
            'description' => 'Old Description',
            'status' => 'pending',
            'due_date' => now()->addDays(7)->format('Y-m-d'),
        ]);

        // Act as the user
        $this->actingAs($user);

        // Act: Update the task
        $response = $this->put("/tasks/{$task->id}", [
            'title' => 'New Title',
            'description' => 'New Description',
            'status' => 'completed',
            'due_date' => now()->addDays(10)->format('Y-m-d'),
        ]);

        // Assert: Task is updated
        $task->refresh(); // Reload the task from the database
        $this->assertEquals('New Title', $task->title);
        $this->assertEquals('New Description', $task->description);
        $this->assertEquals('completed', $task->status);
        $this->assertEquals(now()->addDays(10)->format('Y-m-d'), $task->due_date->format('Y-m-d'));

        // Assert: Redirects correctly with success message
        $response->assertRedirect(route('tasks.list'));
        $response->assertSessionHas('success', 'Task updated successfully.');

        // Assert: Job was dispatched
        Queue::assertPushed(SendEmailToUsersTaskUpdatedJob::class, function ($job) use ($task) {
            // Optionally, add more checks to ensure the job contains the correct task and user data
            return true;
        });
    }

     /** @test */
    public function test_a_logged_in_user_and_role_admin_can_delete_a_task(): void
    {
            // Arrange
            $user = User::factory()->role('Admin')->create();
            $task = Task::factory()->create(); // Ensure you have a Task factory set up
    
            // Act & Assert
            // Ensure the user is authenticated
            $response = $this->actingAs($user)->delete(route('tasks.destroy', $task->id));
    
            // Assert
            // Check if the user is redirected to the correct route with a success message
            $response->assertRedirect(route('tasks.list'));
            $response->assertSessionHas('success', 'Task deleted successfully.');
    
            // Ensure the task is deleted from the database
            $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
