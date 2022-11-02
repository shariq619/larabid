<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GlobalEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $content;
    public $subject;
    public $attachments_1;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content, $subject, $attachments_1 = [])
    {
        $this->content = $content;
        $this->subject = $subject;
        $this->attachments_1 = $attachments_1;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->subject($this->subject)
            ->view('emails.global-email', ['content' => $this->content]);

        foreach ($this->attachments_1 as $filePath) {
            $email->attach($filePath);
        }
        return $email;

    }
}
