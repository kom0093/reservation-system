<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var $this Reservation */
        return $this->getToArray();
    }
}
