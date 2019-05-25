<?php

namespace Extended\Domain\Service;

use Extended\Domain\Contracts\Repository\Repository;

abstract class Service
{
    /**
     * Repository of the service.
     *
     * @var \Extended\Domain\Contracts\Repository\Repository
     */
    protected $repository;

    /**
     * Service dispatcher.
     *
     * @var \Extended\Domain\Service\Dispatcher
     */
    protected static $dispatcher;

    /**
     * Get the dispatcher or resolve if not instantiated.
     *
     * @return  \Extended\Domain\Service\Dispatcher
     */
    public static function dispatcher()
    {
        return static::$dispatcher ?: static::$dispatcher = app(Dispatcher::class);
    }

    /**
     * Forward dispatch call to the dispatcher.
     *
     * @param   string  $event
     * @param   \Closure|array|null $callback
     * @param   array   $parameters
     * @return  mixed
     */
    public function dispatch($event, $callback = null, $parameters = [])
    {
        return static::dispatcher()->dispatch($event, $callback, $parameters);
    }

    /**
     * Handle dynamic method calls into the object.
     *
     * @param   string  $method
     * @param   array   $parameters
     * @return  mixed
     */
    public function __call($method, $parameters)
    {
        // Forward calls to the dispatcher if given module exists.
        if ($this->dispatcher()->hasModule($method)) {
            // Some modules may require parameters in their constructor.
            // If so, and the method exists in this class, call the method to make
            // this as a parameter which will be set as a root of the module.
            if (method_exists($this, $method)) {
                $parameters = $this->{$method}(...$parameters);
            }

            // If parameters is already bound to the dispatcher, get the root of
            // the module because the dispatcher upon dispatching throws an error
            // since methods does not actually exist in this class, which in fact
            // exist in the root.
            if ($parameters instanceof Module) {
                $parameters = $parameters->root();
            }

            // Then, bind the module to the dispatcher.
            return $this->bind($method, $parameters);
        }

        throw new \BadMethodCallException(sprintf(
            'Call to undefined method %s::%s()', static::class, $method
        ));
    }

    public function extends($name, $options)
    {
        return $options;
    }

    /**
     * Bind the given object to the dispatcher.
     *
     * @param   mixed   $object
     * @param   array   $parameters
     * @return  mixed
     */
    protected function bind($object, $parameters)
    {
        return $this->dispatcher()->getModule($object, $parameters);
    }

    /**
     * Get the query builder of the repository bound with dispatcher.
     *
     * @return  mixed
     */
    protected function query()
    {
        return $this->bind('query', $this->repository()->query());
    }

    /**
     * Get the repository of the service bound with dispatcher.
     *
     * @return  \Extended\Domain\Contracts\Repository\Repository
     */
    protected function repository()
    {
        // Make sure that the repository of concrete class implements the contract.
        // Otherwise throw an error to alert them they need to fix.
        if (! $this->repository instanceof Repository) {
            throw new \Exception(sprintf(
                '%s::$repository must be an instance of %s', static::class, Repository::class
            ));
        }

        return $this->bind('repository', $this->repository);
    }

    //

    /**
     * Related resources of the service.
     *
     * @var array
     */
    protected static $resources = [];

    /**
     * Bootstrap and install registered resources.
     *
     * @return  void
     */
    public static function boot()
    {
        static::dispatcher()->install(static::$resources);
    }

    /**
     * Register resources to the service.
     *
     * @param   array   $resources
     * @return  void
     */
    public static function register($resources)
    {
        static::$resources = array_merge(static::$resources, $resources);
    }

    //

    /*public function create($attributes = [])
    {
        $entity = $this->entity($attributes);

        event(new EntityCreated(static::class, $entity));

        return $entity;
    }

    public function store(Entity $entity)
    {
        $entity->save();

        event(new EntityStored(static::class, $entity));

        return $entity;
    }

    /*public static function update($shift, $attributes = [])
    {
        event(new EntityRequestUpdate($shift, $attributes));
    }

    public static function delete($shift)
    {
        event(new EntityRequestDelete($shift));
    }*/

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
