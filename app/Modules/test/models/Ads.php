<?php
namespace App\Modules\test\models;

use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    protected $table = 'ads';
    public $timestamps = false;
    protected $fillable = [
        'department_id',
        'title',
        'is_active',
        'ad_list'
    ];

    protected $casts = [
        'ad_list' => 'array',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}

