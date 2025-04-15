<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class PushNotificationHelper
{
    const AUTHORIZATION_KEY = 'Basic OTIyMmM0ZDktNWU1Mi00OTk0LTg3NTEtYjhjMjEzOGNiNzJi';
    const APP_ID = '83ae6a9d-c12c-42a1-a35b-b12f24d7df26';
    const API_URL = 'https://onesignal.com/api/v1/notifications';
    const CHANNEL = 'push';

    public function sendPush($user_id, array $data)
    {
        $language = Setting::getDeviceLanguage();
        $message = self::achievementMessage($language);

        $response = Http::withHeaders([
            'Authorization' => self::AUTHORIZATION_KEY,
        ])->post(self::API_URL, [
            'app_id' => self::APP_ID,
            'contents' => [
                "en" => $message['contents'],
            ],
            'headings' => [
                "en" => $message['headings'],
            ],
            'data' => $data,
            'channel_for_external_user_ids' => self::CHANNEL,
            'include_external_user_ids' => ["$user_id"],
        ]);

        if ($response->successful()) {
            return $response->json();
        } else {
            return $response->body();
        }
    }

    // The achievementMessage method remains the same
    public function achievementMessage($language)
    {
        if (array_key_exists($language, $this->achievementMessages)) {
            return $this->achievementMessages[$language];
        }

        return $this->achievementMessages['uz-cyril'];
    }


    public array $achievementMessages = [
        'en' => [
            'contents' => 'You have new achievement',
            'headings' => 'You gained one the achievement',
        ],
        'ru' => [
            'contents' => 'У вас новое достижение',
            'headings' => 'Вы получили одно из достижений',
        ],
        'uz-latin' => [
            'contents' => 'Sizda yangi yutuq bor',
            'headings' => 'Siz yutuqqa ega bo\'ldingiz',
        ],
        'uz-cyril' => [
            'contents' => 'Сизда янги ютуқ бор',
            'headings' => 'Сиз битта ютуққа эга бўлдингиз',
        ],
    ];

}
