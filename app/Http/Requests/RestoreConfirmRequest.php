<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestoreConfirmRequest extends FormRequest
{
    public function rules()
    {
        return [
            'token' => 'required|string|regex:/^[a-zA-Z0-9]+$/', // Формат токена: %d-%s
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|same:password',
        ];
    }

    public function authorize()
    {
        return true; // Разрешаем все запросы
    }
}