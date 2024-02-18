<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskCreatedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function build()
    {
        return $this->view('emails.taskCreated')
                    ->subject('New Task Created')
                    ->with([
                        'taskName' => $this->task->title,
                        'taskDescription' => $this->task->description,
                        'taskDueDate' => $this->task->due_date,

                    ]);
    }
}
