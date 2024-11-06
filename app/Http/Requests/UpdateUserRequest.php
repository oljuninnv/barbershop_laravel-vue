<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules()
    {
        return [
            'name' => 'string|max:255',
            'login' => 'string|unique:users,login,'.$this->user->id,
            'email' => 'email|unique:users,email,'.$this->user->id,
            'city' => 'string|max:255',
            'phone' => 'phone',
            'birthday' => 'date_format:Y-m-d',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp'
        ];
    }

    public function authorize()
    {
        return true; // Разрешаем все запросы
    }

    public function messages()
    {
        return [
            'login.unique' => 'Пользователь с таким логином уже существует.',
            'image.mimes' => 'Файл не является изображением',
            'name.max' => 'Имя не должно быть длиннее 255 символов.',
            'email.email' => 'Некорректный формат email.',
            'email.unique' => 'Пользователь с таким email уже существует.',
            'phone.phone' => 'Не верный формат ввода номера телефона',
            'birthday.date_format' => 'Дата рождения должна быть в формате Y-m-d.',
        ];
    }
}
