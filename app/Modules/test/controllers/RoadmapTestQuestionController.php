<?php
namespace App\Modules\test\controllers;

use App\Http\Controllers\Controller;
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
