<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Elegant\Sanitizer\Laravel\SanitizesInput;

class ReservationDeleteRequest extends FormRequest
{
    use SanitizesInput;

    public function authorize(): bool
    {
        $reservation = $this->route('reservation');
        return $reservation && $reservation->user_id === auth()->id();
    }
}
