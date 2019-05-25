<?php

namespace Extended\Domain\Repository;

class Repository
{
    /**
     * Handle dynamic method calls into the object.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($methods, $parameters)
    {
        return $this->query()->$methods(...$parameters);

        // return $this->forwardCallTo($this->query, $method, $parameters);
    }

    /*
    use ForwardsCalls;

    protected $query;

    public function query()
    {
        return new static;
    }
    */
}
