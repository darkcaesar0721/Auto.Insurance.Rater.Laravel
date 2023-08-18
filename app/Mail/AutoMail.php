<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AutoMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $quote;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($quote)
    {
        $this->quote = $quote;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Your Quote - " . $this->quote->hash_id)
                    ->view('emails.auto')
                    ->with('quote', $this->quote);
    }
}
