<?php
namespace App\Models;

use App\Helpers\MediaHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Driver extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;

    protected $fillable = [
        'fullname',
        'date_of_brith',
        'login',
        'pincode',
        'car_model',
        'car_color',
        'car_plate_number',
    ];
 const MEDIA_COLLECTION = 'driver_images';
    protected $dates = ['deleted_at'];


    protected $appends = [
        'fullUrlMedia',
    ];


    public function getFullUrlMediaAttribute()
    {
        return MediaHelper::getMediaByCollection(self::MEDIA_COLLECTION,$this->id)[0] ?? null;
    }

    public function tablets()
    {
        return $this->hasMany(Tablet::class);
    }

    public function carModel()
    {
        return $this->belongsTo(CarModel::class, 'car_model'); // assuming 'car_model' is the foreign key
    }
}
