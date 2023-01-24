<?php

namespace App\Http\Controllers;

use Pusher\Pusher;

class NotificationController extends Controller
{
    public function sendNotification()
    {
        $options = array(
            'cluster' => 'ap2',
            'useTLS' => true
        );
        $pusher = new Pusher(
            '9b4a8055a4250f7a1a9d',
            'f521bec87d8aeea2d709',
            '1542730',
            $options
        );

        $data['message'] = 'Hello World';
        $pusher->trigger('my-channel', 'my-event', $data);
    }
}
