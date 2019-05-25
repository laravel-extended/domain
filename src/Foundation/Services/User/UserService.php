<?php

namespace Extended\Domain\Foundation\Services\User;

use App\Repositories\UserRepository;
use Extended\Domain\Service\Service;

class UserService extends Service
{
    protected static $resources = [
        ProfileService::class
    ];

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function search($search)
    {
        return $this->query()->find($search);
    }
}
