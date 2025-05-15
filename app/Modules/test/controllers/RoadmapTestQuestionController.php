<?php
namespace App\Modules\test\controllers;

use App\Http\Controllers\Controller;
use App\Modules\test\models\Department;
use App\Modules\test\models\RoadmapTest;
use App\Modules\test\service\RoadmapTestQuestionService;
use App\Modules\test\service\RoadmapTestQuestionUpdateService;
use Illuminate\Http\Request;

class RoadmapTestQuestionController extends Controller
{
    protected $testQuestionService;
    protected $testUpdateService;

    public function __construct(
        RoadmapTestQuestionService $testQuestionService,
        RoadmapTestQuestionUpdateService $testUpdateService
    )
    {
        $this->testQuestionService = $testQuestionService;
        $this->testUpdateService = $testUpdateService;
    }

    public function getAllQuestions(Request $request)
    {
        return $this->testQuestionService->getAllQuestionsByTestId($request);
    }

    public function getNextTest()
    {
      return  $departments = Department::has('test')->get();
        $currentDepartment = Department::query()->where('is_next_one',1)->first();


        $priority = 1;
        foreach ($departments as $department) {
            if (is_null($department->priority_number)) {
                $department->priority_number = $priority++;
                $department->save();
            } else {
                $priority = max($priority, $department->priority_number + 1);
            }
        }

        if (!$currentDepartment) {
            $firstDepartment = Department::query()->orderBy('id','desc')->first();
            $firstDepartment->is_next_one = true;
            $firstDepartment->save();
            $currentDepartment = $firstDepartment;
        }

        $tests = RoadmapTest::where('department_id', $currentDepartment->id)->get();

        if ($tests->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No tests found for the current department'
            ], 404);
        }

        $selectedTest = $tests->random();

        $nextDepartment = Department::where('priority_number', '>', $currentDepartment->priority_number)
            ->orderBy('priority_number')
            ->first();

        if (!$nextDepartment) {
            $nextDepartment = Department::orderBy('priority_number')->first();
        }

        foreach ($departments as $department) {
            $department->is_next_one = false;
            $department->save();
        }

        if ($nextDepartment) {
            $nextDepartment->is_next_one = true;
            $nextDepartment->save();
        }
        return response()->json([
            'success' => true,
            'selected_test' =>
            [
                'test_info'=> RoadmapTest::with('ad')->find($selectedTest->id),
                'test_content'=> $this->testQuestionService->getQuestionByTestId($selectedTest->id),
            ],
            'next_department_id' => $nextDepartment->id ?? null
        ]);
    }

    public function getSingleQuestion(Request $request)
    {
        return $this->testQuestionService->getTestQuestionByTestId($request);
    }

    public function createTestQuestionWithLanguage(Request $request)
    {
        return $this->testQuestionService->upsertRoadmapTestQuestionAndOptions($request);
    }


    public function createTestQuestionTypeMatching(Request $request)
    {
        return $this->testQuestionService->upsertMatchingTypeQuestions($request);
    }


    public function deleteTestQuestion(Request $request)
    {
        return $this->testQuestionService->deleteTestQuestion($request->input('id'));
    }
}
