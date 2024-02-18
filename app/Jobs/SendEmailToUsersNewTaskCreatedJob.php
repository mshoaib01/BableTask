<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskCreatedEmail;
use App\Mail\TaskUpdatedEmail;


class SendEmailToUsersNewTaskCreatedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $users;
    public $task;
    /**
     * Create a new job instance.
     */
    public function __construct($users, Task $task)
    {
        $this->users = $users;
        $this->task = $task;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
            foreach ($this->users as $user) {
                Mail::to($user->email)->send(new TaskCreatedEmail($this->task));
            }
        
    }
}
