<?php

namespace App\Modules\test\service;

use App\Modules\Roadmap\roadmapLesson\models\RoadmapLesson;
use App\Modules\test\models\RoadmapTest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class RoadmapTestLanguageService
{

    public static function upsertRoadmapTest(Request $request)
    {
        $model = $request->input('id') ? RoadmapTest::find($request->input('id')) : new RoadmapTest();

        $model->setTranslation('title_', $request->input('language'), $request->input('description'));
        $model->setTranslation('description_', $request->input('language'), $request->input('description'));

        $model->fill($request->all());
        $model->save();

    }

}
