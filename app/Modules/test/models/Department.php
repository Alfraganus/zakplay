<?php

namespace App\Modules\test\models;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Department extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $table = 'department';

    protected $fillable = [
        'department_name_',
        'is_active',
        'is_next_one',
        'priority_number'
    ];


    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('is_active', 1);
        });
    }

    public function getTitleAttribute()
    {
        return json_decode($this->attributes['department_name_'],true)[Setting::getLanguage()] ?? Setting::NO_DATA_MESSAGE;
    }

    public function test()
    {
        return $this->hasMany(\App\Modules\test\models\RoadmapTest::class);
    }
}
