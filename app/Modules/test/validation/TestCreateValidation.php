<?php

namespace App\Modules\test\validation;

use Illuminate\Foundation\Http\FormRequest;

class TestCreateValidation extends FormRequest
{
    public static function rules()
    {
        return [
            'questionInfo' => 'required|array',
            'questionInfo.test_id' => 'nullable|integer',
            'questionInfo.question_text' => 'required|string',
            'questionInfo.question_option_type' => 'required|integer',
            'questionInfo.points' => 'required|numeric',

//            'optionsInfo' => 'required|array|min:2',
            'optionsInfo.*.question_id' => 'nullable|integer',
            'optionsInfo.*.option_text' => 'required|string',
            'optionsInfo.*.is_correct' => 'required|boolean',
            'optionsInfo.*.points' => 'required|numeric',
        ];
    }

    public static function Testrules()
    {
        return [
            'testInfo' => 'required|array',
            'testInfo.title' => 'required|string',
            'testInfo.description' => 'required|string',
            'testInfo.lesson_id' => 'nullable|integer',
            'testInfo.purpose_id' => 'required|integer',
        ];
    }
}
