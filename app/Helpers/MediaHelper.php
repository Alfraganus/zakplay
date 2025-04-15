<?php

namespace App\Helpers;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaHelper
{
    public static function getMediaByCollection($collection, $object_id)
    {
        $media = self::getMedia($object_id,$collection);
        $result = [];
        foreach ($media as $data) {
            $result[] = sprintf("/storage/%d/%s", $data['id'], $data['file_name']);
        }
        return $result;
    }

    public function getMediaByUUID($collection, $multi_language_uuid)
    {
        $media = self::getMedia($multi_language_uuid,$collection);
        $result = [];
        foreach ($media as $data) {
            $result[] = sprintf("/storage/%d/%s", $data['id'], $data['file_name']);
        }
        return $result;
    }

    private static function getMedia($object_id,$collection)
    {
       return Media::query()
            ->where('model_id', $object_id)
            ->where('collection_name', $collection)
            ->get()
            ->toArray();
    }

    public static function getSingleMedia($collection, $object_id)
    {
        $media = Media::query()
            ->where('model_id', $object_id)
            ->where('collection_name', $collection)
            ->first();

        if(!$media) return null;

        return sprintf("/storage/%d/%s", $media['id'], $media['file_name']);

    }
}
