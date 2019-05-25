<?php

namespace Extended\Domain\Foundation\Services\User;

use Extended\Domain\Foundation\Http\Tasks\StoreProfileTask;
use Extended\Domain\Contracts\Repository\ProfileRepository;
use Extended\Domain\Service\Service;

class ProfileService extends Service
{
    public function __construct(ProfileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function install($dispatcher)
    {
        // $dispatcher->replace();

        $dispatcher->merge('request::store', [
            'x' => 'test'
        ]);

        $dispatcher->extend('repository::query', function($query) {
            // $query->where('id', 1);
        });

        $dispatcher->extend('query::find', function($entity) {
            $entity->name = 'Ronald';
        });

        $dispatcher->extend('view::create', function () {
            return view('domain::user.profile.components.create');
        });

        // $dispatcher->listen('action::store', new StoreProfileTask($dispatcher, $this));

        /*
        new StoreProfileTask($dispatcher, $this);

        $dispatcher->listen(['query::get'], function($collection) {
            // $query->where('id', 1);
        });

        $dispatcher->listen(['query::find'], function($model) {
            $this->setProfile($model);
        });
        */
    }

    protected function setProfile($model)
    {
        $model->name = $this->getProfileName($model);
    }

    protected function getProfileName($model)
    {
        return $this->repository()->getNameFromUser($model);
    }
}
