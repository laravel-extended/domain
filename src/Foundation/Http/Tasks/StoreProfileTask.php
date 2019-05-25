<?php

namespace Extended\Domain\Foundation\Http\Tasks;

use Extended\Domain\Foundation\Http\Requests\StoreProfileRequest;
use Extended\Domain\Task;

class StoreProfileTask extends Task
{
    protected $action = 'controller::store';

    protected function boot()
    {
        parent::boot();

        //
        $this->dispatcher->listen('request::store', (new StoreProfileRequest)->rules());
    }

    public function render()
    {
        return view('domain::user.profile.components.create');
    }

    public function handle(StoreProfileRequest $request)
    {
        // $this->service->
    }
}
