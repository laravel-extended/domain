<?php

namespace Extended\Domain\Foundation\Services\Photo;

// use App\Domain\Employee\Photo;
// use App\Domain\Employee\Tasks\UploadPhotoTask;
// use Illuminate\Http\Request;
use Extended\Domain\Contracts\Repository\PhotoRepository;
use Extended\Domain\Service\Service;
use Extended\Domain\Support\Hydrator;
use Illuminate\Support\Facades\Storage;

class PhotoService extends Service
{
    // protected $storage = 'public/photos';

    public function __construct(PhotoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function subscribe($dispatcher)
    {
        $dispatcher->listen(['query::get', 'query::paginate'], function ($models) {
            $this->loadPhotos($models);
        });

        $dispatcher->listen('view::show', function ($data) {
            return view('photo.components.photo', [
                'path' => $this->url($this->repository()->person($data['person']))
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

    protected function loadPhotos($models)
    {
        Hydrator::loadOne(
            $models,
            $photos = $this->repository()->getByOwner($models->pluck('id')),
            'person'
        );
    }
}
