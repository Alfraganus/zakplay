<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserDevices extends Model
{
    use HasFactory;

    protected $table = "user_devices";

    protected $fillable = [
        'user_id',
        'device_token',
    ];

    public static function registerNewDeviceToken($user_id, $device_token)
    {
        $model = self::query()
            ->where('user_id', $user_id)
            ->where('device_token', $device_token)
            ->exists();
        if (!$model) {
            $newDeviceToken = new self();
            $newDeviceToken->user_id = $user_id;
            $newDeviceToken->device_token =$device_token;
            $newDeviceToken->save();
        }
    }

    public static function getUserIdByToken($device_token, $getUserObject = false)
    {
        $model = self::query()->where('device_token',$device_token)->first();
        if($model) {
            $userModel = User::find($model->user_id);
            return $getUserObject ? $userModel :$userModel->id;
        }
        return false;
    }
}
