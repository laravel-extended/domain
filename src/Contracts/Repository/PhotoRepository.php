<?php

namespace Extended\Domain\Contracts\Repository;

interface PhotoRepository extends Repository
{
    public function create($attributes);

    public function person($person);
}
