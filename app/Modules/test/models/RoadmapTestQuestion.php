<?php

namespace App\Modules\test\models;

use App\Helpers\MediaHelper;
use App\Models\Setting;
use App\Modules\test\service\RoadmapTestSubmitService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class RoadmapTestQuestion extends Model implements HasMedia
{
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;


    protected $table = "roadmap_test_questions";
    public $question_match_type;

    public const MEDIA_COLLECTION = 'roadmap-test-question';

    public $translatable = ['question_text_'];

    protected $fillable = [
        'test_id',
        'question_text_',
        'question_option_type',
        'points',
    ];

    protected $appends = [
        'fullUrlMedia',
        'question_match_type',
    ];


    public function getQuestionMatchTypeAttribute()
    {
        if ($this->question_option_type == RoadmapTestSubmitService::QUESTION_TYPE_MATCHING_OPTIONS) {
            $questionText = json_decode(
                $this->attributes['question_text_'],true
            ) [Setting::getLanguage()] ?? Setting::NO_DATA_MESSAGE;

            if (is_array($questionText)) {
                return [
                    'keys'=>array_keys($questionText),
                    'values'=>array_values($questionText),
                    'full'=>$questionText
                ];
            }
        }
    }

    public function getFullUrlMediaAttribute()
    {
        return MediaHelper::getMediaByCollection(self::MEDIA_COLLECTION,$this->id)[0] ?? null;
    }

    public function getQuestionTextAttribute()
    {
        if ($this->question_option_type == 3) {
            return null;
        }
        return json_decode(
            $this->attributes['question_text_'],true
        ) [Setting::getLanguage()] ?? Setting::NO_DATA_MESSAGE;
    }

    public function options()
    {
        return $this->hasMany(RoadmapTestQuestionOption::class, 'question_id');
    }
}
