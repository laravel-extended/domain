<?php

namespace Extended\Domain\Foundation\Services\Photo\Tasks;

// use App\Domain\Employee\Services\PhotoService;
use Extended\Domain\Foundation\Services\Photo\PhotoService;
use Illuminate\Http\Request;

class StorePhotoTask
{
    protected $storage = 'public/photos';

    protected $service;

    protected $request;

    public function __construct(PhotoService $service, Request $request)
    {
        $this->service = $service;

        $this->request = $request;
    }

    public function handle($owner)
    {
        if (is_null($photo = $this->request->file('photo'))) {
            return;
        }

        $this->service->store([
            'person' => $owner->id,
            'path' => $photo->store($this->storage)
        ]);
    }
}
