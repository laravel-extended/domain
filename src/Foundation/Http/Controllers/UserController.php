<?php

namespace Extended\Domain\Foundation\Http\Controllers;

use Extended\Domain\Foundation\Services\User\UserService;
use Extended\Domain\Service\Controller;
use Extended\Domain\Foundation\Http\Requests\StoreUserRequest;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        // $users = $this->service->alias('query::get')->paginate(25);

        $users = $this->service->query()->paginate(15);

        return view('domain::user.index', ['users' => $users, 'service' => $this->service]);
    }

    public function show($id)
    {
        $user = $this->service->query()->find($id);

        return view('domain::user.show', ['user' => $user, 'service' => $this->service]);
    }

    public function create()
    {
        // return $this->service->view('domain::user.create');

        return view('domain::user.create', ['service' => $this->service]);
    }

    public function store(StoreUserRequest $request)
    {
        // $this->service->repository()->store();

        // redirect
    }
}
