<?php

namespace App\Modules\test\models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoadmapTestAnswers extends Model
{
    use HasFactory;

    protected $table = 'roadmap_answers';

    protected $fillable = [
        'test_id',
        'device_id',
        'question_id',
        'option_id',
    ];

    public function test()
    {
        return $this->belongsTo(RoadmapTest::class, 'test_id');
    }

    public function question()
    {
        return $this->belongsTo(RoadmapTestQuestion::class, 'question_id');
    }

    public function options()
    {
        return $this->belongsTo(RoadmapTestQuestionOption::class, 'option_id');
    }
}
