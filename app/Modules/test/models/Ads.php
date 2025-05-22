<?php
namespace App\Modules\test\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Ads extends Model
{
    protected $table = 'ads';
    protected $fillable = [
        'department_id',
        'title',
        'is_active',
        'ad_type',
        'view_count',
        'ad_list'
    ];

    protected $casts = [
        'ad_list' => 'array',
    ];


    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}

