<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Elegant\Sanitizer\Laravel\SanitizesInput;

class RegisterRequest extends FormRequest
{
    use SanitizesInput;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|same:password|min:6',
        ];
    }

    public function filters(): array
    {
        return [
            'first_name' => 'trim',
            'last_name' => 'trim',
            'email' => 'trim|lowercase',
        ];
    }
}
