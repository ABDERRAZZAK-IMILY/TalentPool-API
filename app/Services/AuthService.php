<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Password;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->create($data);
        $token = JWTAuth::fromUser($user);
        return ['user' => $user, 'token' => $token];
    }

    public function login(array $credentials)
    {
        return JWTAuth::attempt($credentials);
    }

    public function getAuthenticatedUser()
    {
        return JWTAuth::user();
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }


    public function refresh()
    {
        try {
            $token = JWTAuth::getToken();

            if (!$token) {
                return null;
            }

            $user = JWTAuth::toUser($token);

            if (!$user) {
                return null;
            }

            $user->tokens()->delete();

            $newToken = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user,
                'token' => $newToken
            ];

        } catch (\Exception $e) {
            return null;
        }
    }


    public function forgotPassword($email)
    {
        $status = Password::sendResetLink(['email' => $email]);
        return $status;
    }


    public function resetPassword($token, $password)
    {
        try {
            $user = JWTAuth::toUser($token);

            if (!$user) {
                return response()->json(['message' => 'Invalid token'], 400);
            }

            $user->password = Hash::make($password);
            $user->save();


            return response()->json(['message' => 'Password has been reset successfully']);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Unable to reset password'], 400);
        }
    }
}