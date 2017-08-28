<?php

namespace App\Mail;

use App\Correspondence;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewIncomeCorrespondence extends Mailable
{
    use Queueable, SerializesModels;

    private $correspondence;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Correspondence $correspondence)
    {
        $this->correspondence = $correspondence;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'))
            ->view('mail.newIncomeCorrespondence')->with([
                'url' => route('page.correspondence.show', ['correspondence' => $this->correspondence->id])
            ])->subject('Новая регистрационная карточка входящего документа');
    }
}
