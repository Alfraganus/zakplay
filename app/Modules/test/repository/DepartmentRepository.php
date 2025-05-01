<?php

namespace App\Modules\test\repository;

use App\Modules\test\models\Department;

class DepartmentRepository
{
    private $model;

    public function __construct(Department $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
        $user = $this->getById($id);
        $user->update($data);

        return $user;
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function getByKey($key,$value)
    {
        return $this->model::query()->where($key,$value);
    }

    public function delete($id)
    {
        $model = $this->model->find($id);

        if (!$model) {
            return response()->json([
                'success' => false,
                'message' => 'Department not found'
            ], 404);
        }

        $model->delete();

        return response()->json([
            'success' => true,
            'message' => 'Department deleted successfully'
        ]);
    }

}
