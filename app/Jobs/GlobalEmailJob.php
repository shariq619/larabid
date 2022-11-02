<?php

namespace App\Jobs;

use App\Mail\GlobalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use PharIo\Manifest\Email;

class GlobalEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */


    protected $content;
    protected $subject;
    protected $email;
    protected $attachments;


    public function __construct($content, $subject, $email, $attachments = [])
    {

        $this->content = $content;
        $this->subject = $subject;
        $this->email = $email;
        $this->attachments = $attachments;
    }


    public function handle()
    {

        $email = new GlobalEmail($this->content, $this->subject, $this->attachments);
        Mail::to($this->email)->send($email);


    }
}
