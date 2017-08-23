<?php

namespace App\Mail;

use App\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DocumentDeclined extends Mailable
{
    use Queueable, SerializesModels;

    private $document;
    /**
     * Create a new message instance.
     *
     * @param Document $document
     * @return void
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'))
            ->view('mail.documentDeclined')->with([
                'url' => route('page.document.show', ['document' => $this->document->id])
            ])->subject('Ваш документ был отклонен в саглосовании');
    }
}
