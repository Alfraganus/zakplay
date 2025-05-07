<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'fullname',
        'date_of_brith',
        'login',
        'pincode',
        'car_model',
        'car_color',
        'car_plate_number',
    ];

    protected $dates = ['deleted_at'];

    public function tablets()
    {
        return $this->hasMany(Tablet::class);
    }

}
