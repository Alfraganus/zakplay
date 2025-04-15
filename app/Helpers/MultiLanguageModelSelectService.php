<?php

namespace App\Helpers;

use App\Models\MultiLanguage;
use App\Modules\Dhikr\Models\Dua;
use App\Modules\Hadith\Models\Hadith;
use App\Modules\Qurans\models\QuranFacts;
use App\Modules\Qurans\models\QuranList;
use App\Modules\Roadmap\roadmap\models\Roadmap;
use App\Modules\Roadmap\roadmapLesson\models\RoadmapLesson;
use App\Modules\Roadmap\test\models\RoadmapTest;
use App\Modules\Roadmap\test\models\RoadmapTestQuestion;
use App\Modules\Roadmap\test\models\RoadmapTestQuestionOption;
use Illuminate\Http\Request;

class MultiLanguageModelSelectService
{

    public function getMultiLanguageData(Request $request, $classname)
    {
        $multilanguageData = MultiLanguage::query()->where(['class_name' => $classname])->get();
        $languageColumn = $classname instanceof QuranList ? "language_code" : "language";
        $result = [];

        foreach ($multilanguageData as $ml_data) {
            $models = $classname::query()
                ->where('multi_language_id', trim($ml_data->uuid));
            if ($request->toArray()) {
                $models->where($request->toArray());
            }
            $models = $models->get();

            if ($models->isNotEmpty()) {
                $mediaCollection = $classname::MEDIA_COLLECTION ?? null;
                $temp = [
                    'uuid' => $ml_data->uuid,
                    'media' => $mediaCollection ? MediaHelper::getMediaByCollection($mediaCollection, $ml_data->id) : [],
                    'data' => [],
                ];
                /*making additonal data depending on model type*/
                $this->exceptionInformations($classname,$temp,$ml_data);

                foreach ($models as $item) {
                    $language = $item[$languageColumn];
                    $temp['data'][$language]/*[]*/ = $item;
                }
                $result[] = $temp;
            }
        }
        return [
            'result' => $result
        ];
    }

    public static $languageColumn = [
        Dua::class => 'language',
        Hadith::class => 'language',
        QuranFacts::class => 'language',
        QuranList::class => 'language_code',
        Roadmap::class => 'language',
        RoadmapLesson::class => 'language',
        RoadmapTest::class => 'language',
        RoadmapTestQuestion::class => 'language',
        RoadmapTestQuestionOption::class => 'language',
    ];

    private function exceptionInformations($classname, &$temp, $ml_data)
    {
        switch ($classname) {
            case app($classname) instanceof RoadmapLesson:
                $temp['tests'] = MultiLanguageModelService::splitDataToLanguages(
                    RoadmapTest::query()->where('roadmap_lesson_uuid', $ml_data->uuid)->get()
                );
                break;
            case app($classname) instanceof RoadmapTestQuestion:
                $temp['options'] = MultiLanguageModelService::splitDataToLanguages(
                    RoadmapTestQuestionOption::query()->where('roadmap_test_question_uuid', $ml_data->uuid)->get()
                );
                break;
            default:
                break;
        }
        return $temp;

    }
}
