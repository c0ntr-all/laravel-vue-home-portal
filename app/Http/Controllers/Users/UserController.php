<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;

class UserController extends Controller
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(auth()->user());
    }

    public function show(): array
    {
        return $this->userResource(auth()->getToken()->get());
    }

    public function store(StoreRequest $request): array
    {
        $user = $this->user->create($request->validated()['user']);

        auth()->login($user);

        return $this->userResource(auth()->refresh());
    }

    public function update(UpdateRequest $request): array
    {
        auth()->user()->update($request->validated()['user']);

        return $this->userResource(auth()->getToken()->get());
    }

    protected function userResource(string $jwtToken): array
    {
        return ['user' => ['token' => $jwtToken] + auth()->user()->toArray()];
    }
}
