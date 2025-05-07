<?php
namespace App\Modules\test\models;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'test_id',
        'test_result',
        'percentage',
        'max_score',
        'device_id',
        'is_passed',
        'average_time',
        'leaderboard_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function test()
    {
        return $this->belongsTo(RoadmapTest::class, 'test_id');
    }
}
