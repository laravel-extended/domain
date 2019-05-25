<?php

namespace Extended\Domain;

use JsonSerializable;

class ValueObject implements JsonSerializable
{
    protected $attribute = [];

    public function __get($key)
    {
        return $this->attribute[$key];
    }

    public function jsonSerialize()
    {
        return $this->attribute;
    }
}
