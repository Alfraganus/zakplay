<?php

namespace App\Modules\test\models;

use App\Models\scopes\DefaultLanguage;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Spatie\Translatable\HasTranslations;

class RoadmapTestQuestionOption extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $table = "roadmap_test_question_options";
    public $translatable = ['option_text_'];
    protected $fillable = [
        'question_id',
        'option_text_',
        'is_correct',
        'points',
    ];

    public function getOptionTextAttribute()
    {
        return json_decode($this->attributes['option_text_'],true)[Setting::getLanguage()] ?? Setting::NO_DATA_MESSAGE;
    }

    public function question()
    {
        return $this->belongsTo(RoadmapTestQuestion::class, 'question_id');
    }
}
