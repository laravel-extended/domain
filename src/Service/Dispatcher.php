<?php

namespace Extended\Domain\Service;

use Illuminate\Support\Str;
use Illuminate\Events\Dispatcher as EventDispatcher;

class Dispatcher
{
    /**
     * Namespace of modules
     *
     * @var string
     */
    protected $namespace = __NAMESPACE__.'\\Modules\\';

    /**
     * Dispatcher that handles dispatching of events
     *
     * @var \Illuminate\Events\Dispatcher|string
     */
    protected $dispatcher;

    /**
     * Create an object instance
     *
     * @param   \Illuminate\Events\Dispatcher $dispatcher
     * @return  void
     */
    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Install event listeners of each service resources.
     *
     * @param   array   $resources
     * @return  void
     */
    public function install($resources)
    {
        foreach ($resources as $resource) {
            if (! method_exists($resource, 'install')) {
                continue;
            }

            $this->resolve($resource)->install($this);
        }
    }

    /**
     * Dispatch events and execute the given callback.
     *
     * @param   string  $event
     * @param   \Closure|array|null $callback
     * @param   array   $parameters
     * @param   string|null $strategy
     * @return  mixed
     */
    public function dispatch($event, $callback = null, $parameters = [], $strategy = null)
    {
        //
        $parameters = array_merge(
            $parameters,
            $this->dispatcher->dispatch($event.':before', $parameters)
        );

        // if (! is_callable($callback)) { // @todo: throw error if [object, method] does not exist
        if (is_null($callback) || (is_array($callback) && is_null($callback[0]))) {
            return $this->dispatcher->dispatch($event, $parameters);
        }

        if (! method_exists($callback[0], $callback[1])) {
            if (is_array($callback[0])) {
                dd($callback[0]);
            }
            throw new \BadMethodCallException(sprintf(
                'Call to undefined method %s::%s()', get_class($callback[0]), $callback[1]
            ));
        }

        if (! $result = call_user_func_array($callback, $parameters)) {
            return $result;
        }

        return tap($result, function ($result) use ($event) {
            $this->dispatcher->dispatch($event, $result);
        });
    }

    public function hasModule($module)
    {
        return class_exists($this->namespace.Str::studly($module));
    }

    public function getModule($module, ...$parameters)
    {
        return $this->getCallHandler($module, $parameters);
    }


    public function getCallHandler($module, $parameters)
    {
        // dump($parameters);

        return $this->resolveModule($module, $parameters);
    }

    public function merge($event, $options)
    {
        $callback = function () use ($options) {
            return $options;
        };

        $this->dispatcher->listen($event, $callback);
    }

    /**
     * Create an event that extends the given callback.
     *
     * @param   string  $event
     * @param   \Closure    $callback
     * @param   string  $strategy
     * @return  void
     */
    public function extend($event, $callback, $strategy = '')
    {
        if (is_array($event)) {
            return $this->extendArray($event, $callback);
        }

        $this->dispatcher->listen($event, $callback);

        // [$module, $name] = $this->parse($event);

        // $this->resolveModule($module);
    }

    /**
     * Create an array of events that extends the given callback.
     *
     * @param   array   $events
     * @param   \Closure    $callback
     * @return  void
     */
    protected function extendArray($events, $callback)
    {
        foreach ($events as $event) {
            $this->extend($event, $callback);
        }
    }

    protected function resolve($resource, $parameters = [])
    {
        if (is_string($resource)) {
            return resolve($resource, $parameters);
        }

        return $resource;
    }

    /**
     * Create instance of the module
     *
     * @param   string  $module
     * @param   array   $parameters
     * @return  \Extended\Domain\Service\Module
     */
    protected function resolveModule($module, $parameters = [])
    {
        $class = $this->namespace.Str::studly($module);

        return new $class($this, ...$parameters);
    }

    /**
     * Parse the event name separated by `::`
     *
     * @param   string  $event
     * @return  string
     */
    protected function parse($event)
    {
        return explode('::', $event);
    }
}
