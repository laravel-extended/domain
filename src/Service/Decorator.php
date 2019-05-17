<?php

namespace Extended\Domain\Service;

class Decorator
{
    protected $dispatcher;

    protected $namespace;

    protected $object;

    public function __construct(Dispatcher $dispatcher, $namespace, $object)
    {
        $this->dispatcher = $dispatcher;

        $this->namespace = $namespace;

        $this->object = $object;
    }

    /*public function __get($property)
    {
        $this->objecct->$property
    }*/

    public function __call($method, $parameters)
    {
        return $this->dispatcher->dispatch(
            $this->namespace.'::'.$method,
            [$this->object, $method],
            $parameters
        );
    }

    /*public function __debugInfo()
    {
        $x = var_export($this->object, true);
        // dump($x);
        return [];
        // return var_export($this->object ?: new stdClass, true);
    }*/
}
