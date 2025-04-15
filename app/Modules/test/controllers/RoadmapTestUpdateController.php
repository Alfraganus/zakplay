<?php

namespace App\Modules\test\controllers;

use App\Http\Controllers\Controller;
use App\Modules\test\service\RoadmapTestQuestionUpdateService;
use Illuminate\Http\Request;

class RoadmapTestUpdateController extends Controller
{
    protected $testService;

    public function __construct(RoadmapTestQuestionUpdateService $testService)
    {
        $this->testService = $testService;
    }

    public function __invoke(Request $request)
    {
        return $this->testService->updateRoadmapTest($request);
    }


}
