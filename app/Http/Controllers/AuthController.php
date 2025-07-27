<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $token = auth()->guard('api')->attempt($request->only('email', 'password'));

        if (!$token) {
            return $this->error('Chybný email nebo heslo');
        }

        $this->loggedInUser = auth('api')->user();

        return $this->respondWithToken($token);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        if (!$user) {
            return $this->error('Chyba v registraci');
        }

        return $this->success('Registrace proběhla úspěšně. Nyní se můžete přihlásit.');
    }

    public function profile(): JsonResponse
    {
        if ($this->loggedInUser) {
            return $this->success($this->loggedInUser->getToArray());
        }

        return $this->unauthorized();
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();
        return $this->success('Odhlášení proběhlo úspěšně');
    }

    protected function respondWithToken(string $token): JsonResponse
    {
        return $this->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $this->loggedInUser->getToArray(),
        ]);
    }
}
