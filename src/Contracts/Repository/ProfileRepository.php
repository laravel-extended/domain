<?php

namespace Extended\Domain\Contracts\Repository;

interface ProfileRepository // extends Repository
{
    public function getNameFromUser($model);
}
