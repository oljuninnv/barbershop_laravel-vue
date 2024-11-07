<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class RecordRequest extends FormRequest
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
     // Правила валидации
     public function rules()
     {
         return [
             'user_id' => 'nullable|integer|exists:users,id',
             'worker_id' => 'required|integer|exists:workers,id',
             'date' => 'required|date',
             'time' => 'required|date_format:H:i:s',
             'user_name' => 'nullable|string|max:100',
             'user_email' => 'nullable|email',
             'user_phone' => 'nullable|phone',
         ];
     }
 
     // Обработка ошибок валидации
     protected function failedValidation(Validator $validator)
     {
         throw new ValidationException($validator, response()->json([
             'errors' => $validator->errors(),
         ], 422));
     }
 
     // Сообщения об ошибках валидации
     public function messages()
     {
         return [
             'worker_id.required' => 'Поле worker_id обязательно для заполнения.',
             'worker_id.integer' => 'Поле worker_id должно быть целым числом.',
             'worker_id.exists' => 'Указанный worker_id не существует.',
             'user_id.exists' => 'Данный пользователь не существует',
             'date.required' => 'Поле date обязательно для заполнения.',
             'date.date' => 'Поле date должно быть корректной датой.',
             'time.required' => 'Поле time обязательно для заполнения.',
             'time.date_format' => 'Поле time должно быть в формате HH:MM.',
             'user_email.email' => 'Поле user_email должно быть корректным адресом электронной почты.',
             'user_phone.phone' => 'Не верный формат ввода номера телефона',
             'user_name.string' => 'Имя должно быть строкой',
             'user_name.max' => 'Имя не должно быть длиннее 100 символов.',
         ];
     }
}
