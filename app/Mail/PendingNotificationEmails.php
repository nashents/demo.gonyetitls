<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PendingNotificationEmails extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $category;
    public $company;
    public $number;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($company, $category, $number)
    {
        $this->category = $category;
        $this->company = $company;
        $this->number = $number;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.pending_notifications')
        ->from($this->company->noreply)
        ->subject($this->category . ' Approval Notification Email');
    }
}
