<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class ServiceRequest extends FormRequest
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
            'name' => 'required|string|max:50', // Имя обязательно, строка, максимум 50 символов
            'price' => 'required|numeric|min:0', // Цена обязательна, должна быть числом, не меньше 0 и не больше 10000
            'execution_time' => 'required|date_format:H:i:s', // Время выполнения обязательно, должно соответствовать формату времени
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json([
            'error' => 'Ошибка в заполнении данных.',
            'messages' => $validator->errors(),
        ], 422)); // Изменен код на 422 для валидационных ошибок
    }

    /**
     * Сообщения об ошибках валидации.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Имя является обязательным полем.',
            'name.max' => 'Имя не должно быть длиннее 50 символов.',
            'price.required' => 'Цена является обязательным полем.',
            'price.numeric' => 'Цена является числом',
            'price.min' => 'Цена не должна быть меньше 0',
            'execution_time.required' => 'Время выполнения является обязательным полем.',
            'execution_time.date_format' => 'Время выполнения должно соответствовать формату времени "H:i:s"',
        ];
    }
}
