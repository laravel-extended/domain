<?php

namespace Extended\Domain\Foundation\Services\Photo\Integration;

class PersonService
{
    protected $service;

    public function __construct(PhotoService $service)
    {
        $this->service = $service;
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
}
