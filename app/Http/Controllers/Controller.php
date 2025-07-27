<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

abstract class Controller
{
    public ?User $loggedInUser = null;

    public function __construct() {
        $this->loggedInUser = auth()->user();
    }
    public function unauthorized(Collection|array|string|null $data = null): JsonResponse
    {
        if (is_string($data)) {
            $data = ['message' => $data];
        }
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }
        return response()->json($data ?? ['message' => 'Váš token expiroval'], 401);
    }

    public function permissionDenied(Collection|array|string|null $data = null): JsonResponse
    {
        if (is_string($data)) {
            $data = ['message' => $data];
        }
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }
        return response()->json($data ?? ['message' => 'Nemáte oprávnění k této akci.'], 403);
    }

    public function success(Collection|array|string|null $data = null): JsonResponse
    {
        if (is_string($data)) {
            $data = ['message' => $data];
        }
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }
        return response()->json($data ?? ['message' => 'Změna byla úspěšně provedena.']);
    }

    public function error(Collection|array|string|null $data = null): JsonResponse
    {
        if (is_string($data)) {
            $data = ['message' => $data];
        }
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }
        return response()->json($data ?? ['message' => 'Nespecifikovaná chyba.'], 400);
    }

    public function notFound(Collection|array|string|null $data = null): JsonResponse
    {
        if (is_string($data)) {
            $data = ['message' => $data];
        }
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }
        return response()->json($data ?? ['message' => 'Entita nebyla nalezena.'], 404);
    }
}
