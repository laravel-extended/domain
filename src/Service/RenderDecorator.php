<?php

namespace Extended\Domain\Service;

use Illuminate\Support\HtmlString;

class RenderDecorator extends Decorator
{
    // protected $dispatcher;

    protected $namespace = 'view';

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function render($name, $parameters = [])
    {
        return new HtmlString(join($this->{$name}($parameters)));
    }
}
