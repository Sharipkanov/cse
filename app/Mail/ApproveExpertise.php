<?php

namespace App\Mail;

use App\Expertise;
use App\ExpertiseInfo;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApproveExpertise extends Mailable
{
    use Queueable, SerializesModels;

    private $expertiseInfo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ExpertiseInfo $expertiseInfo)
    {
        $this->expertiseInfo = $expertiseInfo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'))
            ->view('mail.approveDocument')->with([
                'url' => route('page.expertise.info.show', ['expertiseInfo' => $this->expertiseInfo->id])
            ])->subject('Новая экспертиза на подтверждение');
    }
}
