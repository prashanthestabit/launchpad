<?php

namespace App\Listeners;

use App\Events\NewUserApproved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApprovedEmail;

class SendAprovedEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\NewUserApproved  $event
     * @return void
     */
    public function handle(NewUserApproved $event)
    {
        try{
            $user = $event->user;
            Mail::to($user->email)->send(new ApprovedEmail($user));
        }catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

    }
}
