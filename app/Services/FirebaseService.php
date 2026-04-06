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

        // Cek platform token dari database
        $fcmToken = \App\Models\FcmToken::where('token', $token)->first();
        $platform = $fcmToken?->platform ?? 'web';

        // Bangun payload berdasarkan platform
        $message = ["token" => $token];

        if ($platform === 'ios') {
            // ✅ iOS butuh notification block
            $message["notification"] = [
                "title" => $title,
                "body"  => $body,
            ];
            $message["data"] = [
                "title" => $title,
                "body"  => $body,
                "url"   => $url,
            ];
            $message["apns"] = [
                "payload" => [
                    "aps" => [
                        "alert" => [
                            "title" => $title,
                            "body"  => $body,
                        ],
                        "sound" => "default",
                        "badge" => 1,
                    ]
                ]
            ];
        } else {
            // ✅ Android & web - hanya data block
            // Service worker yang handle tampilan notifikasi
            $message["data"] = [
                "title" => $title,
                "body"  => $body,
                "url"   => $url,
            ];
            $message["android"] = [
                "priority" => "high",
            ];
        }

        return Http::withToken($accessToken)->post(
            "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send",
            ["message" => $message]
        );
    }
}
