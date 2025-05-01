<?php
namespace App\Modules\test\models;

use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    protected $table = 'ads';

    protected $fillable = [
        'department_id',
        'title',
        'is_active',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}

