<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarModel extends Model
{
    protected $table = 'car_models';
    use SoftDeletes;
    protected $fillable = [
        'brand',
        'name',
    ];


    public static array $carColors = [
        1 => 'Oq',
        2 => 'Qora',
        3 => 'Kulrang',
        4 => 'Kumushrang',
        5 => 'Qizil',
        6 => 'Ko‘k',
        7 => 'Havorang',
        8 => 'Yashil',
        9 => 'Jigarrang',
        10 => 'Sariq',
        11 => 'To‘q yashil',
        12 => 'To‘q ko‘k',
        13 => 'Bej',
        14 => 'Apelsin rang',
        15 => 'Oltin rang',
        16 => 'Bronza rang',
        17 => 'Binafsha',
        18 => 'Pushti',
        19 => 'Ko‘kish',
        20 => 'Grafit rang',
    ];

    public function carModel()
    {
        return $this->belongsTo(CarModel::class, 'car_model')->withTrashed();
    }

}
