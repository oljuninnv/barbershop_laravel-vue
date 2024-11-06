<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'login' => 'required|string|unique:users,login',
            'city' => 'required|string|max:255',
            'phone' => 'required|phone',
            'birthday' => 'required|date_format:Y-m-d',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp'
        ];
    }

    public function messages()
    {
        return [
            'login.required' => 'Логин является обязательным полем.',
            'login.unique' => 'Пользователь с таким логином уже существует.',
            'image.mimes' => 'Файл не является изображением',
            'name.required' => 'Имя является обязательным полем.',
            'name.max' => 'Имя не должно быть длиннее 255 символов.',
            'email.required' => 'Email является обязательным полем.',
            'email.email' => 'Некорректный формат email.',
            'email.unique' => 'Пользователь с таким email уже существует.',
            'city.required' => 'Город является обязательным полем.',
            'phone.required' => 'Номер телефона является обязательным полем.',
            'phone.phone' => 'Не верный формат ввода номера телефона',
            'birthday.required' => 'Дата рождения является обязательным полем.',
            'birthday.date_format' => 'Дата рождения должна быть в формате Y-m-d.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json([
            'error' => 'Ошибка в заполнении данных.',
            'messages' => $validator->errors(),
        ], 422)); // Изменен код на 422 для валидационных ошибок
    }
}
