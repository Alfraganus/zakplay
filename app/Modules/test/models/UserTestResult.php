<?php
namespace App\Modules\test\models;
use App\Models\User;
use App\Modules\test\service\RoadmapTestSubmitService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public static function getUsersRank(Request $request, $test_id)
    {
        $topUsers = UserTestResult::where('user_test_results.test_id', $test_id)
            ->join('users', 'users.id', '=', 'user_test_results.user_id')
            ->select('user_test_results.user_id', 'users.name', DB::raw('MAX(user_test_results.test_result) as max_result'))
            ->groupBy('user_test_results.user_id', 'users.name')
            ->orderByDesc('max_result')
            ->take(10)
            ->get();

        $userRank = UserTestResult::where('test_id', $test_id)
            ->where('user_id', RoadmapTestSubmitService::getUserData($request)->id)
            ->first();

        $userRankNumber = null;
        if ($userRank) {
            $userRankNumber = UserTestResult::where('test_id', $test_id)
                ->where('test_result', '>=', $userRank->test_result)
                ->count();
        }

        return [
            'top_users'=>$topUsers,
            'userRankNumber'=>$userRankNumber,
        ];
    }
    public function test()
    {
        return $this->belongsTo(RoadmapTest::class, 'test_id');
    }
}
