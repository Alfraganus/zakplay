<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class OrmHelper
{
    public function getAllByLanguage($modelClassname, $fields)
    {
        App::setLocale(Setting::getLanguage());
        $model = (new $modelClassname());
        return $model->all()->select($fields);
    }

    public function getWithAllLanguage($classname)
    {
        return $classname::query()->get();
    }

    public function create(Model $model, Request $request, $attachmentName = null)
    {
        if (property_exists($model, 'translatable')) {
            foreach ($model->translatable as $field) {
                if ($request->has($field)) {
                    $model->setTranslation($field, $request->input('language'), $request->input($field));
                }
            }
        }
        $data = $request->except($model->translatable);
        $model->fill($data)->save();
        if ($request->hasFile($attachmentName ??'image')) {
            $model
                ->addMediaFromRequest($attachmentName ??'image')
                ->toMediaCollection($model::MEDIA_COLLECTION);
        }
        return $model;
    }


    public function update(Model $model, Request $request, $id, $attachmentName = null)
    {
        $companion = $model->findOrFail($id);
        if (property_exists($model, 'translatable')) {
            foreach ($model->translatable as $field) {
                if ($request->has($field)) {
                    $companion->setTranslation($field, $request->input('language'), $request->input($field));
                }
            }
        }
        $data = $request->except($model->translatable);
        $companion->fill($data)->save();
        if ($request->hasFile('image') ) {
            if ($companion->hasMedia($model::MEDIA_COLLECTION)) {
                $companion->clearMediaCollection($model::MEDIA_COLLECTION);
            }
            $companion
                ->addMediaFromRequest($attachmentName)
                ->toMediaCollection($model::MEDIA_COLLECTION);
        }
        return $companion;
    }

    public function delete($model)
    {
        if ($model->hasMedia($model::MEDIA_COLLECTION)) {
            $model->clearMediaCollection($model::MEDIA_COLLECTION);
        }
        $model->delete();

        return response()->json(['message' => 'Model deleted successfully.'], 200);
    }

}
