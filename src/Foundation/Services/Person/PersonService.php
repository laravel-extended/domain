<?php

namespace Extended\Domain\Foundation\Services\Person;

use Extended\Domain\Service\Service;
use Extended\Domain\Contracts\Repository\Repository;
use Illuminate\Support\HtmlString;

class PersonService extends Service
{
    // use RepositoryConstraint;

    // protected static $namespace = 'Extended\Domain\Foundation\Services';

    protected static $resources = [
        'contact' => \Extended\Domain\Foundation\Services\Person\ContactService::class,
        'photo' => \Extended\Domain\Foundation\Services\Photo\PhotoService::class,
    ];

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /*public function store()
    {
        //
    }*/

    public function update($id, $attributes)
    {
        return $this->dispatch('model::update', function () use ($id, $attributes) {
            return tap($this->find($id), function ($model) use ($attributes) {
                // $model->update($attributes);
            });
        });

        // return $this->find($id)->update($attributes);
    }

    /*
    public function update($id, $attributes = [])
    {
        return $this->hook('model::update', function () use ($id, $attributes) {
            return tap($this->find($id), function ($entity) use ($attributes) {
                $entity->update($attributes);
                // $this->hook('model::updated', $entity);
            });
        });
    }

    /*
    public function store($attributes = [])
    {
        $employee = Employee::create($attributes);

        // event(new EmployeeCreated($employee));

        $this->hook('task::created');

        NewEmployeeTask::dispatchNow($employee);

        // NewEmployeeTask::withChain([$employee])->dispatch();

        return $employee;
    }
    */
}
