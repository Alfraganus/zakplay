<?php

namespace App\Helpers;

use App\Modules\test\models\RoadmapTestQuestion;
use App\Modules\test\models\RoadmapTestQuestionOption;
use Illuminate\Http\Request;

class MultiLanguageModelService
{
    public static function roadmapGlobalInsert(Request $request, $modelClass, $fields = [], $attachment_name = null)
    {
        $model = $request->input('id') ? $modelClass::find($request->input('id')) : new $modelClass();
        foreach ($fields as $field) {
            $model->setTranslation($field . "_", $request->input('language'), $request->input($field));
        }
        $model->fill($request->all());
        $model->save();

        if ($request->file($attachment_name ??'image')) {
            if ($model->hasMedia($model::MEDIA_COLLECTION)) {
                $model->clearMediaCollection($model::MEDIA_COLLECTION);
            }
            $model->addMedia($request->file($attachment_name ??'image'))
                ->toMediaCollection($model::MEDIA_COLLECTION);
        }
        return $model;
    }

    private static function getColumnLanguages($requestData, $columnWithLanguage)
    {
        /* spatisedagi formatni parse qilish, u yerda title_ ichida 3 til ketgan*/
        $columnWithLanguages = $requestData[$columnWithLanguage . '_'];
        if (isset($columnWithLanguages)) {
            return [
                'languages' => array_keys($columnWithLanguages),
                'data' => $columnWithLanguages
            ];
        }
        return [];
    }

    public static function roadmapLessonInsert($request, $modelClass, $fields, $roadmap_id,$sender)
    {
        $model = new $modelClass();
        self::fillLanguageColumns($fields, $request, $model);
        $model->roadmap_id = $roadmap_id;
        $model->fill($request);
        $model->save();
        $hasImage = $request['fullUrlMedia'][0] ?? null;
        if(isset($hasImage)) {
            $url = $sender == 'dev' ? ExportRoadmapLessonService::DEV :  ExportRoadmapLessonService::PROD;
            $model->addMediaFromUrl($url.'/'.$hasImage)->toMediaCollection(RoadmapLesson::MEDIA_COLLECTION);
        }
        return $model;
    }

    public static function roadmapTestInsert($request, $modelClass, $fields, $lesson_id)
    {
        $model = new $modelClass();
        $model->setLocale('en');
        self::fillLanguageColumns($fields, $request, $model);
        $model->lesson_id = $lesson_id;
        $model->fill($request);
        $model->save();

        return $model;
    }

    public static function testQuestionInsert($data, $test_id)
    {
        $result = [];
        foreach ($data as $testQuestion) {
            unset($testQuestion['test_id']);
            $model = new RoadmapTestQuestion();
            self::fillLanguageColumns(['question_text'], $testQuestion, $model);
            $model->fill($testQuestion);
            $model->test_id = $test_id;
            $model->save();
            $result['question'][] = $model;
            if ($testQuestion['options']) {
                foreach ($testQuestion['options'] as $option) {
                    unset($option['question_id']);
                    $optionModel = new RoadmapTestQuestionOption();
                    $optionModel->fill($option);
                    $optionModel->question_id = $model->id;
                    $optionModel->save();
                    $result['option'][] = $optionModel;
                }
            }
        }
        return $result;
    }

    private static function fillLanguageColumns($fields, $request, $model)
    {
        foreach ($fields as $field) {
            $getTitles = self::getColumnLanguages($request, $field);
            if (sizeof($getTitles) > 0) {
                foreach ($getTitles['languages'] as $language) {
                    $value = $getTitles['data'][$language];
                    if ($value) $model->setTranslation($field . "_", $language, $value);
                }
            }
        }
    }
}
