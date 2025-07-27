<?php

declare(strict_types=1);

namespace App\Http\Services;

use Carbon\Carbon;
use App\Models\Table;
use App\Models\Reservation;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ReservationSaveRequest;
use Illuminate\Pagination\LengthAwarePaginator;

class ReservationService extends Service
{
    public function storeReservation(ReservationSaveRequest $request, Reservation $reservation = null): string
    {
        $validated = $request->validated();
        $data = [];

        $data['datetime'] = Carbon::createFromFormat(
            'Y-m-d H:i',
            $validated['date'].' '.$validated['time']
        );

        $data['user_id'] = $this->loggedInUser->getId();

        $firstSuitableTable = Table::where('capacity', '>=', $validated['person_count'])
            ->whereDoesntHave('reservation', function ($query) use ($validated) {
                $query->where('datetime', $validated['date'].' '.$validated['time'].':00');
            })
            ->orderBy('capacity')
            ->first();

        if (!$firstSuitableTable) {
            return $this->setStatus('TABLE_NOT_FREE_ERROR', 'V tomto termínu již není volný stůl.', 422);
        }

        $data['table_id'] = $firstSuitableTable->getId();

        return $this->save($data, $reservation);
    }

    public function save(array $data, Reservation $reservation = null): string
    {
        if (!$reservation) {
            $reservation = Reservation::create($data);
        } else {
            $reservation->update($data);
        }

        return $this->setStatus('SAVED');
    }

    public function getUserReservations(bool $includePast): LengthAwarePaginator
    {
        return $this->loggedInUser->reservations()
            ->when(!$includePast, static function ($query) {
                return $query->where('datetime', '>=', now());
            })
            ->orderBy('datetime')
            ->paginate(10);
    }

    public function getAvailableTimes(string $date, int $personCount): array
    {
        $allTimes = ['11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00'];
        $availableTimes = [];

        $reservations = Reservation::whereDate('datetime', $date)->get();
        $tables = Table::where('capacity', '>=', $personCount)->get();

        $nowPlusOneHour = now()->addHour();

        foreach ($allTimes as $time) {
            // if date is now + 1 hour, skip past
            if ($date === $nowPlusOneHour->toDateString() && $nowPlusOneHour->format('H:i') >= $time) {
                continue;
            }

            $availableTableExists = $tables->contains(function ($table) use ($reservations, $date, $time) {
                $isReserved = $reservations->contains(function ($res) use ($table, $date, $time) {
                    return $res->table_id === $table->id &&
                        $res->datetime->format('Y-m-d') === $date &&
                        $res->datetime->format('H:i') === $time;
                });

                return !$isReserved;
            });

            if ($availableTableExists) {
                $availableTimes[] = $time;
            }
        }

        return $availableTimes;
    }

    public function getResponse(): JsonResponse
    {
        return match ($this->getStatus()) {
            'SAVED' => $this->setResponseMessage('Rezervace byla úspěšně vytvořena.'),
            'TABLE_NOT_FREE_ERROR' => $this->customError(),
            default => $this->notSpecifiedError(),
        };
    }
}
