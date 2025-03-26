<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;

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

    public function refresh()
    {
        return response()->json([
            'token' => Auth::refresh()
        ]);
    }

    public function me()
    {
        return response()->json(Auth::user());
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function resetPassword()
    {
        $data = request()->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        $this->authService->resetPassword($data['email'], $data['password']);
        return response()->json(['message' => 'Password reset successfully']);
    }

    public function forgotPassword()
    {
        $data = request()->validate([
            'email' => 'required|email'
        ]);
        $this->authService->forgotPassword($data['email']);
        return response()->json(['message' => 'Password reset link sent to your email']);
    }

}