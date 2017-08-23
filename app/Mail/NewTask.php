<?php

namespace App\Mail;

use App\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewTask extends Mailable
{
    use Queueable, SerializesModels;

    private $task;

    /**
     * Create a new message instance.
     *
     * Task $task
     * @return void
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'))
            ->view('mail.newTask')->with([
                'url' => route('page.task.show', ['task' => $this->task->id])
            ])->subject('Новое карточка задания');
    }
}
