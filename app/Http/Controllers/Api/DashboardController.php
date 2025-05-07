<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Tablet;
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
        return response()->json([
            'tablet_count' => Tablet::count(),
            'driver_count' => Driver::count(),
            'roadmap_test_count' => RoadmapTest::count(),
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
        // Set the department ID from the request
        $departmentId = $request->input('department_id');

        // Use current week for start and end dates
        $startDate = now()->startOfWeek();
        $endDate = now()->endOfWeek();

        // Fetch statistics for the current week, grouped by department and day
        $data = DB::table('roadmap_test')
            ->selectRaw('DAYNAME(created_at) as day, DAYOFWEEK(created_at) as day_of_week, department_id, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupByRaw('DAYNAME(created_at), DAYOFWEEK(created_at), department_id')
            ->orderByRaw('FIELD(day_of_week, 2, 3, 4, 5, 6, 7, 1)') // Order days from Monday to Sunday
            ->get();

        // List of weekdays for reference
        $weekDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        // Initialize result array to store counts for each department and day
        $result = [];

        // Populate initial result structure for departments, if not found in data
        $departments = DB::table('department')->get(); // Assuming departments are stored in a 'department' table
        foreach ($departments as $department) {
            $result[$department->department_name_] = [];
            foreach ($weekDays as $day) {
                $result[$department->department_name_][$day] = 0;
            }
        }

        // Populate the result array with actual data from the query
        foreach ($data as $item) {
            $dayName = $item->day;

            // Safely fetch the department name, defaulting to 'Unknown' if not found
            $departmentName = optional(DB::table('department')->where('id', $item->department_id)->first())->department_name_ ?? 'Unknown';

            $result[$departmentName][$dayName] = $item->total;
        }

        return response()->json([
            'department_id' => $departmentId,
            'range' => [$startDate->toDateString(), $endDate->toDateString()],
            'data' => $result,
        ]);
    }






}
