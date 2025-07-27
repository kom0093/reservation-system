<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Enums\OrderStateEnum;
use Illuminate\Http\JsonResponse;
use App\Http\Services\ReservationService;
use App\Http\Resources\ReservationResource;
use App\Http\Requests\ReservationSaveRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ReservationController extends Controller
{
    public function __construct(protected ReservationService $reservationService)
    {
        parent::__construct();
    }

    public function list(Request $request): AnonymousResourceCollection
    {
        $includePast = (bool)$request->query('past');

        $reservations = $this->reservationService->getUserReservations($includePast);

        return ReservationResource::collection($reservations);
    }

    public function getAvailableTimes(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'person_count' => 'required|integer|min:1',
        ]);
        
        $availableTimes = $this->reservationService->getAvailableTimes($validated['date'], (int) $validated['person_count']);
        return $this->success(['message' => 'success', 'available_times' => $availableTimes]);
    }

    public function save(ReservationSaveRequest $request): JsonResponse
    {
        $this->reservationService->storeReservation($request);
        return $this->reservationService->getResponse();
    }

    public function delete(Reservation $reservation): JsonResponse
    {
        if ($reservation->user_id !== auth()->id()) {
            return $this->permissionDenied();
        }

        $reservation->delete();
        return $this->success();
    }
}
