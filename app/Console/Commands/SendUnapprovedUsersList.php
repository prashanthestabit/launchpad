<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\UnapprovedUsersList;

class SendUnapprovedUsersList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:unapproved-users-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a scheduler to trigger a mail to admin daily the list of unapproved user’s list.';

    const STATUSPENDING = 1;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $unapprovedUsers = User::where('status_id', self::STATUSPENDING)->get();

        if($unapprovedUsers){
            Mail::to(config('mail.admin'))->send(new UnapprovedUsersList($unapprovedUsers));
        }

    }
}
