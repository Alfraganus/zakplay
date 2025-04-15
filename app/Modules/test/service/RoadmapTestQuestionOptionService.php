<?php
namespace App\Modules\test\service;

use App\Modules\test\models\RoadmapTestQuestionOption;
use App\Modules\test\repository\RoadmapTestQuestionOptionRepository;

class RoadmapTestQuestionOptionService
{
    protected $repository;

    public function __construct(RoadmapTestQuestionOptionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createWithLanguage($data)
    {
        $model = isset($data['id']) ? RoadmapTestQuestionOption::find($data['id']) : new RoadmapTestQuestionOption();
        $model->setTranslation('option_text_', $data['language'], $data['option_text']);
        $model->fill($data)->save();

        return $model;
    }

    public function createOption($data)
    {
        return $this->repository->create($data);
    }

    public function updateOption($data)
    {
        return $this->repository->update($data);
    }
}
