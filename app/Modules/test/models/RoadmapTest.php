<?php

namespace App\Modules\test\models;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class RoadmapTest extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $table = "roadmap_test";

    const MEDIA_COLLECTION = "roadmap_test";

    public $translatable = ['title_', 'description_'];

    protected $fillable = [
        'title_',
        'description_',
        'department_id',
    ];

    public function getDescriptionAttribute()
    {
        return json_decode($this->attributes['description_'],true)[Setting::getLanguage()] ?? Setting::NO_DATA_MESSAGE;
    }

    public function getTitleAttribute()
    {
        return json_decode($this->attributes['title_'],true)[Setting::getLanguage()] ?? Setting::NO_DATA_MESSAGE;
    }

    public function questions()
    {
        return $this->hasMany(RoadmapTestQuestion::class, 'test_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

}
