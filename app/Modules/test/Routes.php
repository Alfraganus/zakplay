<?php

use App\Modules\test\controllers\RoadmapTestController;
use App\Modules\test\controllers\RoadmapTestQuestionController;
use Illuminate\Support\Facades\Route;




Route::get('/test', function () {
    return 200;
});
Route::post('/zakplay/test/answers/show', [RoadmapTestController::class, 'getAnswers']);
    Route::post('/zakplay/test/answers/submit', [RoadmapTestController::class, 'submitAnswers']);
    Route::post('/zakplay/test/create', [RoadmapTestController::class, 'createTest']);
    Route::get('/zakplay/test/find-by-id/{id}', [RoadmapTestController::class, 'findById']);
    Route::put('/zakplay/test/update/{id}', [RoadmapTestController::class, 'updateTest']);
    Route::delete('/zakplay/test/delete', [RoadmapTestController::class, 'deleteTest']);

    Route::get('/zakplay/test-question/get-single-question', [RoadmapTestQuestionController::class, 'getSingleQuestion']);
    Route::get('/zakplay/test-question/get-all-questions', [RoadmapTestQuestionController::class, 'getAllQuestions']);
    Route::post('/zakplay/test-question/create', [RoadmapTestQuestionController::class, 'createTestQuestion']);
    Route::post('/zakplay/test-question/update', [RoadmapTestQuestionController::class, 'updateTestQuestion']);
    Route::delete('/zakplay/test-question/delete', [RoadmapTestQuestionController::class, 'deleteTestQuestion']);

    Route::post('/zakplay/test-question/upsert', [RoadmapTestQuestionController::class, 'createTestQuestionWithLanguage']);
    Route::post('/zakplay/test/upsert', [RoadmapTestController::class, 'upsertRoadmapTest']);
    Route::post('/zakplay/test-question/upsert-matching', [RoadmapTestQuestionController::class, 'createTestQuestionTypeMatching']);


