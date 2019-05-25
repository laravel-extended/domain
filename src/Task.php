<?php

namespace Extended\Domain;

abstract class Task
{
    protected $dispatcher;

    protected $service;

    public function __construct($dispatcher, $service)
    {
        $this->boot($this->dispatcher = $dispatcher);

        $this->service = $service;
    }

    protected function boot()
    {
        $this->dispatcher->listen('view::create', [$this, 'render']);

        /*
        $this->dispatcher->listen($this->action.':before', function () {
            $request = resolve(StoreProfileRequest::class); // validate
        });
        */

        // $dispatcher->listen('controller::store');

        //

        // $dispatcher
    }
}
