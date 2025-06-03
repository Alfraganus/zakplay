<?php

namespace App\Models;

use App\Modules\test\models\RoadmapTest;
use App\Modules\test\models\UserTestResult;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaderboardResult extends Model
{
    use HasFactory;

    protected $table = 'leaderboard_results';

    protected $fillable = [
        'leaderboard_id',
        'test_id',
        'test_result_id',
        'is_special_leaderboard',
    ];

    protected $casts = [
        'is_special_leaderboard' => 'boolean',
    ];

    public function leaderboard()
    {
        return $this->belongsTo(Leaderboard::class);
    }

    public function test()
    {
        return $this->belongsTo(RoadmapTest::class);
    }

    public function testResult()
    {
        return $this->belongsTo(UserTestResult::class, 'test_result_id');
    }
}
