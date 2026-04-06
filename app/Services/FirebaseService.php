<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Google\Auth\Credentials\ServiceAccountCredentials;

class FirebaseService
{
    protected $projectId = 'upt-smksw';

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
        $url = $url ?? '/admin/requests';

        return Http::withToken($accessToken)->post(
            "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send",
            [
                "message" => [
                    "token" => $token,

                    // ✅ data block - untuk service worker ambil URL
                    "data" => [
                        "title" => $title,
                        "body"  => $body,
                        "url"   => $url,
                    ],

                    // ✅ webpush block - khusus PWA (iOS & Android browser)
                    "webpush" => [
                        "notification" => [
                            "title" => $title,
                            "body"  => $body,
                            "icon"  => "/logo.png",
                            "badge" => "/logo.png",
                            "data"  => ["url" => $url]
                        ],
                        "fcm_options" => [
                            "link" => $url
                        ]
                    ],

                    // ✅ android block - pastikan Android tetap normal
                    "android" => [
                        "notification" => [
                            "title"        => $title,
                            "body"         => $body,
                            "icon"         => "ic_notification",
                            "click_action" => "FLUTTER_NOTIFICATION_CLICK"
                        ]
                    ]
                ]
            ]
        );
    }
}
