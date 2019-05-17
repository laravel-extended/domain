<?php

namespace Extended\Domain\Service;

use Illuminate\Support\Traits\ForwardsCalls;

abstract class Service
{
    use ForwardsCalls;

    protected $repository;

    /**
     * Handle dynamic method calls into the object.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->default(), $method, $parameters);
    }

    protected function default()
    {
        return $this->query();
    }

    //

    protected function query()
    {
        return $this->decorate('query', $this->repository()->query());
    }

    protected function repository()
    {
        return $this->decorate('repository', $this->repository);
    }

    //

    public function view($name, $parameters = [])
    {
        $render = new RenderDecorator(static::dispatcher());

        return $render->render($name, $parameters);
    }

    //

    protected static $dispatcher;

    public static function dispatcher()
    {
        return static::$dispatcher ?: static::$dispatcher = app(Dispatcher::class);
    }

    public function dispatch($event, $callback = null, $parameters = [])
    {
        return static::dispatcher()->dispatch($event, $callback, $parameters);
    }

    public function decorate($namespace, $callback)
    {
        return new Decorator(static::dispatcher(), $namespace, $callback);
    }

    //

    protected static $resources = [];

    public static function boot()
    {
        static::dispatcher()->subscribes(static::$resources);
    }

    public static function register($resources)
    {
        static::$resources = array_merge(static::$resources, $resources);
    }

    //

    /*
    protected $container;

    protected $resource;

    protected function container()
    {
        return $this->container ?: $this->container = app();
    }

    public function resource($resource = null)
    {
        return $resource ?: $this->resource ?: strtolower(str_replace('Service', '', class_basename(static::class)));

        // return $this->resource ?: Str::slug(Str::snake(class_basename($this->entity)));
    }
    */
}
