<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class RecordServicesRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'record_id' => 'required|integer|exists:records,id',
            'service_id' => 'required|integer|exists:services,id',
            'total_price' => 'required|numeric|min:0',
            'total_duration' => 'required|date_format:H:i',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'record_id.required' => 'Поле record_id обязательно для заполнения.',
            'record_id.integer' => 'Поле record_id должно быть целым числом.',
            'record_id.exists' => 'Указанный record_id не существует.',
            'service_id.required' => 'Поле service_id обязательно для заполнения.',
            'service_id.integer' => 'Поле service_id должно быть целым числом.',
            'service_id.exists' => 'Указанный service_id не существует.',
            'total_price.required' => 'Поле total_price обязательно для заполнения.',
            'total_price.numeric' => 'Поле total_price должно быть числом.',
            'total_price.min' => 'Поле total_price не может быть отрицательным.',
            'total_duration.required' => 'Поле total_duration обязательно для заполнения.',
            'total_duration.date_format' => 'Поле total_duration должно быть в формате HH:MM.',
        ];
    }
}
