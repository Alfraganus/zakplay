<?php

use App\Modules\test\controllers\RoadmapTestController;
use App\Modules\test\controllers\RoadmapTestQuestionController;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/roadmap/test/answers/show', [RoadmapTestController::class, 'getAnswers']);
    Route::post('/roadmap/test/answers/submit', [RoadmapTestController::class, 'submitAnswers']);
    Route::post('/roadmap/test/create', [RoadmapTestController::class, 'createTest']);
    Route::get('/roadmap/test/find-by-id/{id}', [RoadmapTestController::class, 'findById']);
    Route::put('/roadmap/test/update/{id}', [RoadmapTestController::class, 'updateTest']);
    Route::delete('/roadmap/test/delete', [RoadmapTestController::class, 'deleteTest']);

    Route::get('/roadmap/test-question/get-single-question', [RoadmapTestQuestionController::class, 'getSingleQuestion']);
    Route::get('/roadmap/test-question/get-all-questions', [RoadmapTestQuestionController::class, 'getAllQuestions']);
    Route::post('/roadmap/test-question/create', [RoadmapTestQuestionController::class, 'createTestQuestion']);
    Route::post('/roadmap/test-question/update', [RoadmapTestQuestionController::class, 'updateTestQuestion']);
    Route::delete('/roadmap/test-question/delete', [RoadmapTestQuestionController::class, 'deleteTestQuestion']);

    Route::post('/roadmap/test-question/upsert', [RoadmapTestQuestionController::class, 'createTestQuestionWithLanguage']);
    Route::post('/roadmap/test/upsert', [RoadmapTestController::class, 'upsertRoadmapTest']);
    Route::post('/roadmap/test-question/upsert-matching', [RoadmapTestQuestionController::class, 'createTestQuestionTypeMatching']);
});
