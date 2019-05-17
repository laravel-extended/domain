<?php

namespace Extended\Domain\Foundation\Services\Photo;

// use App\Domain\Employee\Photo;
// use App\Domain\Employee\Tasks\UploadPhotoTask;
// use Illuminate\Http\Request;
use Extended\Domain\Contracts\Repository\PhotoRepository as Repository;
use Extended\Domain\Service\Service;
use Illuminate\Support\Facades\Storage;

class PhotoService extends Service
{
    // protected $storage = 'public/photos';

    protected $key = 'person';

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function subscribe($dispatcher)
    {
        $dispatcher->listen(['query::get', 'query::paginate'], function ($models) {
            $this->load($models);
        });

        $dispatcher->listen('view::show', function ($data) {
            return view('photo.components.photo', [
                'path' => $this->url($this->repository()->person($data[$this->key]))
            ]);
        });

        $dispatcher->listen('view::edit', function () {
            return view('photo.components.form');
        });

        $dispatcher->listen('model::update', Tasks\StorePhotoTask::class);
    }

    public function store($attributes)
    {
        return $this->dispatch('model::store', function () use ($attributes) {
            //
            return $this->repository()->create($attributes);
        });
    }

    public function url($photo = null)
    {
        return $photo ? Storage::url($photo->path) : '/avatar.png';
    }

    public function load($models)
    {
        $photos = $this->query()->whereIn($this->key, $models->pluck('id'))->get();
        if ($photos->isEmpty()) {
            return;
        }

        $dictionary = [];
        foreach ($photos as $photo) {
            $dictionary[(string) $photo->{$this->key}] = tap($photo, function ($photo) {
                $photo->url = $this->url($photo);
            });
        }

        foreach ($models as $model) {
            $model->photo = ['url' => $this->url()];
            if (isset($dictionary[(string) $model->id])) {
                $model->photo = $dictionary[(string) $model->id];
            }
        }
    }
}
