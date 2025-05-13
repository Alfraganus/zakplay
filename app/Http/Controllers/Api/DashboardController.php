<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Tablet;
use App\Modules\test\models\Department;
use App\Modules\test\models\RoadmapTest;
use Carbon\Carbon;
use Illuminate\Database\Query\Expression;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/dashboard/user-counts",
     *     tags={"Dashboard"},
     *     summary="Get counts of Tablets, Drivers, and RoadmapTests",
     *     description="Returns the total number of Tablets, Drivers, and RoadmapTests",
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="tablet_count", type="integer", example=25),
     *             @OA\Property(property="driver_count", type="integer", example=10),
     *             @OA\Property(property="roadmap_test_count", type="integer", example=5),
     *         )
     *     )
     * )
     */
    public function getCounts(): JsonResponse
    {
        $departments = Department::where('is_active', 1)->get();
        $activeDepartmentIds = $departments->pluck('id')->toArray();

        return response()->json([
            'tablet_count' => Tablet::count(),
            'driver_count' => Driver::count(),
            'roadmap_test_count' => RoadmapTest::where('is_active', 1)
                ->whereIn('department_id', $activeDepartmentIds)
                ->count(),
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/dashboard/weekly-test-stats",
     *     summary="Get weekly test stats by department",
     *     tags={"Dashboard"},
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Start date of the range (optional, defaults to current week start)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-08-12")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="End date of the range (optional, defaults to current week end)",
     *         required=false,
     *         @OA\Schema(type="string", format="date", example="2024-08-18")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="department_id", type="integer", example=1),
     *             @OA\Property(property="range", type="array", @OA\Items(type="string", example="2024-08-12")),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 example={
     *                     "Monday": 5,
     *                     "Tuesday": 2,
     *                     "Wednesday": 0,
     *                     "Thursday": 3,
     *                     "Friday": 7,
     *                     "Saturday": 1,
     *                     "Sunday": 0
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(response=400, description="Invalid input"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */

    public function getWeeklyTestStats(Request $request)
    {
        $departmentId = $request->input('department_id');

        $startDate = now()->startOfWeek();
        $endDate = now()->endOfWeek();

        // Get only active departments
        $departments = Department::where('is_active', 1)->get();
        $activeDepartmentIds = $departments->pluck('id')->toArray();

        // Fetch statistics for the current week, only for active departments
        $data = DB::table('roadmap_test')
            ->selectRaw('DAYNAME(created_at) as day, DAYOFWEEK(created_at) as day_of_week, department_id, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('department_id', $activeDepartmentIds) // ✅ Only include tests from active departments
            ->groupByRaw('DAYNAME(created_at), DAYOFWEEK(created_at), department_id')
            ->orderByRaw('FIELD(day_of_week, 2, 3, 4, 5, 6, 7, 1)')
            ->get();

        $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        $result = [];

        foreach ($departments as $department) {
            $result[$department->department_name_] = [];
            foreach ($weekDays as $day) {
                $result[$department->department_name_][$day] = 0;
            }
        }

        // Fill actual stats
        foreach ($data as $item) {
            $dayName = $item->day;
            $departmentName = optional(
                $departments->firstWhere('id', $item->department_id)
            )->department_name_ ?? 'Unknown';

            if ($departmentName !== 'Unknown') {
                $result[$departmentName][$dayName] = $item->total;
            }
        }

        return response()->json([
            'range' => [$startDate->toDateString(), $endDate->toDateString()],
            'data' => $result,
        ]);
    }






}
