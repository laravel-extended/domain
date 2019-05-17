<?php

namespace Extended\Domain\Foundation\Services\Person;

use Extended\Domain\Service\Service;

class ContactService extends Service
{
    public function subscribe($dispatcher)
    {
        $dispatcher->listen('repository::query', function ($query) {
            // $query->where('id', 1);
        });

        /*$dispatcher->listen('view::show', function () {
            return view('person.photo.components.form');
        });*/

        /*
        $dispatcher->listen('controller::show:before', function ($employee) {
            // dump($employee);
            // return 'test';
        });
        */

        $dispatcher->listen('view::edit', function () {
            return view('employee.components.contact');
        });

        /*$dispatcher->listen('model::update', function ($employee) {
            return;
        });*/
    }
}
