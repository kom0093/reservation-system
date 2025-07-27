<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Elegant\Sanitizer\Laravel\SanitizesInput;
use Illuminate\Foundation\Http\FormRequest;

class ReservationSaveRequest extends FormRequest
{
    use SanitizesInput;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
            'person_count' => 'required|integer',
        ];
    }

    public function filters(): array
    {
        return [
            'date' => 'trim',
            'time' => 'trim',
            'person_count' => 'digit',
        ];
    }
}
