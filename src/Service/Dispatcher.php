<?php

namespace Extended\Domain\Service;

use Illuminate\Events\Dispatcher as EventDispatcher;

class Dispatcher
{
    protected $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function dispatch($event, $callback = null, array $parameters = [])
    {
        $parameters = array_merge(
            $parameters,
            $this->dispatcher->dispatch($event.':before', $parameters)
        );

        // if (! is_callable($callback)) { // @todo: throw error if [object, method] does not exist
        if (is_null($callback) || (is_array($callback) && is_null($callback[0]))) {
            return $this->dispatcher->dispatch($event, $parameters);
        }

        if (! $result = call_user_func_array($callback, $parameters)) {
            return $result;
        }

        return tap($result, function ($result) use ($event) {
            $this->dispatcher->dispatch($event, $result);
        });
    }

    public function subscribes($subscribers)
    {
        foreach ($subscribers as $subscriber) {
            if (! method_exists($subscriber, 'subscribe')) {
                continue;
            }

            $this->dispatcher->subscribe($subscriber);
        }
    }
}
