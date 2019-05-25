<?php

namespace Extended\Domain\Support;

use Illuminate\Support\Facades\Storage;

class Hydrator
{
    public static function loadOne($models, $collection, $key, $foreignId = 'id', $callback = null)
    {
        if ($collection->isEmpty()) {
            return;
        }

        $dictionary = [];
        foreach ($collection as $item) {
            $dictionary[(string) $item->$key] = tap($item, function ($item) {
                $item->url = static::url($item);
            });
        }

        foreach ($models as $model) {
            $model->photo = ['url' => static::url()];
            if (isset($dictionary[(string) $model->id])) {
                $model->photo = $dictionary[(string) $model->id];
            }
        }
    }

    public static function url($photo = null)
    {
        return $photo ? Storage::url($photo->path) : '/avatar.png';
    }
}
