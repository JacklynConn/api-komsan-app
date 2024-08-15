<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Exception\FirebaseException;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $credentialsFile = config('firebase.credentials.file');

        if (!$credentialsFile) {
            Log::error('Firebase credentials file not specified.');
            throw new \InvalidArgumentException('Firebase credentials file not specified.');
        }

        Log::info('Using Firebase credentials file: ' . $credentialsFile);

        $factory = (new Factory)->withServiceAccount($credentialsFile);
        $this->messaging = $factory->createMessaging();
    }

    public function sendNotification($deviceToken, $title, $body, $data = [])
    {
        $notification = Notification::create($title, $body);
        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification($notification)
            ->withData($data);

        try {
            $this->messaging->send($message);
        } catch (FirebaseException $e) {
            // Handle the error accordingly
            throw new \RuntimeException('Failed to send notification: ' . $e->getMessage());
        }
    }
}
