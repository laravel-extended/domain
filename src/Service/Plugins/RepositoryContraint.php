<?php

namespace Extended\Domain\Service\Plugins;

// namespace Khronos\Domain\Service\Concerns;

use Khronos\Domain\Service\Contracts\RoleService as RoleServiceContract;
use Illuminate\Support\Arr;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Traits\ForwardsCalls;

trait HasConstraints
{
    use ForwardsCalls;

    protected static $constraints = [];

    protected static $roles = RoleServiceContract::class;

    protected function roles()
    {
        return $this->container()->make(static::$roles);
    }

    protected function query()
    {
        return $this->repository->query();
    }

    public function constraint($constraint)
    {
        return $this->constrained($constraint);
    }

    protected function constrained($constraints = [])
    {
        $constraints = array_intersect_key(
            static::$constraints,
            array_flip(
                array_merge($this->constraints(), Arr::wrap($constraints))
            )
        );

        return $this->container()->make(Pipeline::class)
            ->send($this->query())
            ->through($constraints)
            ->then(function ($query) {
                return $query;
            });
    }

    protected function constraints($resource = null)
    {
        return $this->roles()->constraints($this->resource($resource));
    }

    /**
     * Handle dynamic method calls into the object.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        /*if (! method_exists($query = $this->query(), $method)) {
            dd('test');
            return $this->throwBadMethodCallException($method);
        }*/

        return $this->forwardCallTo($this->constrained(), $method, $parameters);
    }
}
