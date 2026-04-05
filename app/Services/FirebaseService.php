<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Google\Auth\Credentials\ServiceAccountCredentials;

class FirebaseService
{
    protected $projectId = 'upt-smksw'; // ganti sesuai project kamu

    public function getAccessToken()
    {
        $credentials = new ServiceAccountCredentials(
            ['https://www.googleapis.com/auth/firebase.messaging'],
            json_decode(file_get_contents(storage_path('app/firebase-key.json')), true)
        );

        $token = $credentials->fetchAuthToken();

        return $token['access_token'];
    }

    public function send($token, $title, $body, $url = null)
    {
        $accessToken = $this->getAccessToken();

        return Http::withToken($accessToken)->post(
            "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send",
            [
                "message" => [
                    "token" => $token,
                    "data" => [
                        "title" => $title,
                        "body" => $body,
                        "url" => $url
                    ]
                ]
            ]
        );
    }
}
