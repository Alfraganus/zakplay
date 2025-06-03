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

    public static function checkIfUserTopRank($test_id, $test_result_id)
    {
        $allUsers = UserTestResult::where('test_id', $test_id)
            ->select('id', 'user_id', 'test_result')
            ->orderByDesc('test_result')
            ->get();

        $top_10 = false;
        $top_3 = false;
        $percent_higher_than = 0;

        $totalParticipants = $allUsers->count();
        $userResult = $allUsers->firstWhere('id', $test_result_id);

        if (!$userResult || $totalParticipants === 0) {
            return [
                'top_10' => false,
                'top_3' => false,
                'percent_higher_than' => 0,
            ];
        }

        foreach ($allUsers->take(10) as $index => $user) {
            if ($user->id == $test_result_id) {
                if ($index < 3) {
                    $top_3 = true;
                } else {
                    $top_10 = true;
                }
            }
        }

        $lessCount = $allUsers->filter(function ($u) use ($userResult) {
            return $u->test_result < $userResult->test_result;
        })->count();

        $percent_higher_than = round(($lessCount / $totalParticipants) * 100, 2);

        return [
            'top_10' => $top_10,
            'top_3' => $top_3,
            'percent_higher_than' => $percent_higher_than,
        ];
    }


    public static function getUsersRank(Request $request, $test_id)
    {
        $topUsers = UserTestResult::select('user_test_results.user_id', 'platform_users.fullname', DB::raw('MAX(user_test_results.test_result) as max_result'))
            ->join('platform_users', 'platform_users.id', '=', 'user_test_results.user_id')
            ->groupBy('user_test_results.user_id', 'platform_users.fullname')
            ->orderBy('max_result', 'desc')
            ->orderByDesc('max_result')
            ->take(10)
            ->get();

        $userRank = UserTestResult::where('user_id', RoadmapTestSubmitService::getUserData($request)->id)
            ->first();

        $userRankNumber = null;
        if ($userRank) {
            $userRankNumber = UserTestResult::where('test_result', '>=', $userRank->test_result)
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
