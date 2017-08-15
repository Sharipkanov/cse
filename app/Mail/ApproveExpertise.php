<?php

namespace App\Mail;

use App\Expertise;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApproveExpertise extends Mailable
{
    use Queueable, SerializesModels;

    private $expertise;

    /**
     * Create a new message instance.
     *
     * @param Expertise $expertise
     * @return void
     */
    public function __construct(Expertise $expertise)
    {
        $this->expertise = $expertise;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('sharipkanov@gmail.com')
            ->view('mail.approveExpertise')->with([
                'url' => route('page.expertise.show', ['document' => $this->expertise->id])
            ]);
    }
}
