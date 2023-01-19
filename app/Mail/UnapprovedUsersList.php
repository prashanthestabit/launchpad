<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UnapprovedUsersList extends Mailable
{
    use Queueable, SerializesModels;

    public $unapprovedUsers;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($unapprovedUsers)
    {
        $this->unapprovedUsers = $unapprovedUsers;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.unapproved-users-list')
        ->subject('List of Unapproved Users!')
        ->with([
            'unapprovedUsers' => $this->unapprovedUsers,
        ]);
    }
}
