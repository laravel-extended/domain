<?php

namespace Extended\Domain\Service\Modules;

use Extended\Domain\Service\Module;
use Illuminate\Support\HtmlString;

class View extends Module
{
    protected $namespace = 'view';

    public function render($name, $parameters = [])
    {
        return new HtmlString(join($this->{$name}($parameters)));
    }

    protected function dispatch($event, $method, $parameters)
    {
        return $this->dispatcher->dispatch($event, null, $parameters);
    }
}
