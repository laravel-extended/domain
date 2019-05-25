<?php

namespace Extended\Domain\Service;

use Extended\Domain\Service\Dispatcher;
use Illuminate\Support\Str;

class Module
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var \Extended\Domain\Service\Dispatcher
     */
    protected $dispatcher;

    /**
     * @var mixed
     */
    protected $root;

    /**
     * Create a module instance
     *
     * @param   \Extended\Domain\Service\Dispatcher  $dispatcher
     * @param   mixed|null  $root
     * @return  void
     */
    public function __construct(Dispatcher $dispatcher, $root = null)
    {
        $this->dispatcher = $dispatcher;

        $this->root = $root;
    }

    /**
     * Get the root object of the module
     *
     * @return type
     */
    public function root()
    {
        return $this->root;
    }

    /**
     * Handle dynamic method calls into the object.
     *
     * @param   string  $method
     * @param   array   $parameters
     * @return  type
     */
    public function __call($method, $parameters)
    {
        return $this->dispatch($this->namespace().'::'.$method, $method, $parameters);
    }

    /**
     * Dispatch the event
     *
     * @param   string  $event
     * @param   string  $method
     * @param   array   $parameters
     * @return  mixed
     */
    protected function dispatch($event, $method, $parameters)
    {
        return $this->dispatcher->dispatch($event, [$this->root, $method], $parameters);
    }

    /**
     * Get the namespace or generate based on the class
     *
     * @return  string
     */
    protected function namespace()
    {
        return $this->namespace ?: Str::snake(class_basename(static::class), '-');
    }
}
