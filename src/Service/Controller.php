<?php

namespace Extended\Domain\Service;

class Controller
{
    protected $service;

    /**
     * Execute an action on the controller.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        if (! $this->service instanceof Service) {
            throw new \Exception('No service created');
        }

        return $this->service->dispatch('controller::'.$method, [$this, $method], $parameters);
    }
}
