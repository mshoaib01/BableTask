<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;

class ApiTaskControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
   
    /** @test */
    public function Test_an_authenticated_user_can_retrieve_tasks()
    {
        // Arrange: create a user and task, and authenticate the user
        $user = User::factory()->create();
        // Create a task and associate it with the created user
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/tasks');

        // Assert: Check if tasks are retrieved successfully
        $response->assertOk()
        ->assertJsonStructure([
            '*' => [ // '*' indicates each element in the array
                'id',
                'title',
                'description',
                'status',
                'user_id',
                'due_date',
                'created_at',
                'updated_at'
            ]
        ])
        ->assertJsonFragment([
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status,
            'user_id' => $task->user_id,
            'due_date' => $task->due_date,
        ]);
    }

        /** @test */
        public function Test_it_returns_a_failed_message_when_no_tasks_are_found()
        {
            // Arrange: create a user and do not create any tasks
            $user = User::factory()->create();
    
            $response = $this->actingAs($user, 'sanctum')->getJson('/api/tasks');
    
            // Assert: Check the response for no tasks
            $response->assertOk()
                     ->assertJson([
                         'status' => 'failed',
                         'message' => 'No product found!',
                     ]);
        }


        /** @test */
        public function Test_an_authenticated_user_can_update_a_task()
        {
            // Arrange: Create a user and a task associated with that user
            $user = User::factory()->create();
            $task = Task::factory()->create([
                'user_id' => $user->id,
                'title' => 'Original Title',
                'description' => 'Original Description',
                'status' => 'pending',
                'due_date' => '2024-02-29 00:00:00',
            ]);

            // Data for updating the task
            $updatedData = [
                'title' => 'Updated Title',
                'description' => 'Updated Description',
                'status' => 'completed',
                'due_date' => '2024-03-01 00:00:00',
            ];

            // Act: Attempt to update the task as the authenticated user
            $response = $this->actingAs($user, 'sanctum')
                            ->postJson("/api/tasks/update/{$task->id}", $updatedData);

            //check updated value
            $update_task = Task::first();
            $this->assertEquals('Updated Title',$update_task->title);
            $this->assertEquals('Updated Description',$update_task->description);
            $this->assertEquals('completed',$update_task->status);
            $this->assertEquals('2024-03-01 00:00:00',$update_task->due_date);
            $response->assertOk();
        
        }

        /** @test */
        public function Test_an_authenticated_user_can_create_a_task()
        {
            // Arrange: Create a user and a task associated with that user
            $user = User::factory()->create();
            
            // Data for  the task
            $taskData = [
                'title' => 'New Title',
                'description' => 'New Description',
                'status' => 'completed',
                'due_date' => '2024-05-01 00:00:00',
            ];

            // Act: Attempt to update the task as the authenticated user
            $response = $this->actingAs($user, 'sanctum')->postJson("/api/tasks/store", $taskData);

            //check task created or not 
            $this->assertEquals(1,Task::count());

            $response->assertOk();
        }

        /** @test */
        public function Test_an_authenticated_user_can_retrieve_tasks_by_id()
        {
            // Arrange: Create a user and a task associated with that user
            $user = User::factory()->create();

            $task = Task::factory()->create([
                'user_id' => $user->id,
                'title' => 'Original Title',
                'description' => 'Original Description',
                'status' => 'pending',
                'due_date' => '2024-02-29 00:00:00',
            ]);
            // Act: Attempt to get the task by id as the authenticated user
            $response = $this->actingAs($user, 'sanctum')
                            ->getJson("/api/tasks/show/{$task->id}");
            
            // Decode the response to access the task details.
            $retrieveTaskData = $response->json();
            $retrieveTaskID = $retrieveTaskData['data']['id'] ?? null;

            //check task Id matches  
            $this->assertEquals($task->id,$retrieveTaskID);

            $response->assertOk();
        
        }

        /** @test */
        public function Test_an_authenticated_user_can_delete_tasks()
        {
            // Arrange: Create a user and a task associated with that user
            $user = User::factory()->create();

            $task = Task::factory()->create([
                'user_id' => $user->id,
                'title' => 'Original Title',
                'description' => 'Original Description',
                'status' => 'pending',
                'due_date' => '2024-02-29 00:00:00',
            ]);
            // Act: Attempt to delete the task by id as the authenticated user
            $response = $this->actingAs($user, 'sanctum')
                            ->postJson("/api/tasks/destroy/{$task->id}");
            
            // Decode the response to access the task details.
            $retrieveTaskData = $response->json();
            $retrieveTaskID = $retrieveTaskData['data']['id'] ?? null;

            //check task deleted or not 
            $this->assertEquals(0,Task::count());
            $response->assertOk();
        
        }

        



        


    
}
