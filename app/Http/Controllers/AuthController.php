<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $userData = $this->authService->register($request->validated());
        return response()->json($userData, 201);
    }

    public function login(LoginRequest $request)
    {
        $token = $this->authService->login($request->only('email', 'password'));
        return $token
            ? response()->json(['token' => $token])
            : response()->json(['error' => 'the data is not correct'], 401);
    }

    public function me()
    {
        return response()->json($this->authService->getAuthenticatedUser());
    }

    public function logout()
    {
        $this->authService->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
}