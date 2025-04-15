<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redis;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'key', 'value'];

    const NO_DATA_MESSAGE = "";
    const DEFAULT_LANGUAGE = 'uz_cyril';
    const DEVICE_TOKEN = "X-App-Device-Id";
    const TEST_LANGUAGE = 'en';

    public static array $languages = [
        'uz_cyril',
        'uz_latin',
        'ru',
        'en',
    ];

    public static function getLanguage()
    {
        return Request::header('language', Setting::DEFAULT_LANGUAGE);
    }

    public static function getDeviceToken()
    {
        return Request::header(self::DEVICE_TOKEN);
    }

    public static function handleLanguageUpdate($request,$device_token)
    {
        $newLanguage =$request->header('language');

        $redisKey = "language:{$device_token}";

        if (Redis::exists($redisKey)) {
            $currentLanguage = Redis::get($redisKey);
            if ($currentLanguage !== $newLanguage) {
                Redis::set($redisKey, $newLanguage);
                return "Language updated to {$newLanguage}";
            }
        } else {
            Redis::set($redisKey, $newLanguage);
            return "New language set to {$newLanguage}";
        }
    }

    public static function getDeviceLanguage($deviceTokenId = null)
    {
        $deviceToken = $deviceTokenId ?? self::getDeviceToken();
        $redisKey = "language:{$deviceToken}";

        if (Redis::exists($redisKey)) {
            return Redis::get($redisKey);
        }

        return self::DEFAULT_LANGUAGE;
    }

}
