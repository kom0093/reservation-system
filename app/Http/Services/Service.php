<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

abstract class Service
{
    public ?User $loggedInUser = null;

    public function __construct(
        protected ?string $status = null,
        protected ?string $customMessage = null,
        protected array $extraInfo = [],
        protected int $customMessageCode = 400,
    ) {
        $this->loggedInUser = auth()->user();
    }

    public function setStatus(string $status, string $customMessage = null, int $customMessageCode = 400, array $data = null): string
    {
        $this->status = $status;
        $this->customMessage = $customMessage;
        $this->customMessageCode = $customMessageCode;
        if ($data) {
            $this->extraInfo = $data;
        }
        return $status;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setResponseMessage(string $message = null, int $httpCode = 200): JsonResponse
    {
        $this->extraInfo = is_array($this->extraInfo) ? $this->extraInfo : $this->extraInfo->toArray();
        $json = $message ? array_merge(['message' => $message], $this->extraInfo) : $this->extraInfo;
        return response()->json($json, $httpCode);
    }

    public function permissionDenied(): JsonResponse
    {
        return $this->setResponseMessage('Permission denied', 403);
    }

    public function notSpecifiedError(): JsonResponse
    {
        return $this->setResponseMessage('Not specified error', 400);
    }

    public function customError(): JsonResponse
    {
        return $this->setResponseMessage($this->customMessage, $this->customMessageCode);
    }

    abstract public function getResponse(): JsonResponse;
}
