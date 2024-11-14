<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class WorkerRequest extends FormRequest
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
            'work_experience' => 'required|numeric|min:0|max:100',
            'adopted_at' => 'nullable|date|before_or_equal:today',
        ];
    }

    public function messages()
    {
        return [
            'work_experience.required' => 'Опыт работы является обязательным полем.',
            'work_experience.numeric' => 'Опыт работы является числом',
            'work_experience.min' => 'Опыт работы не должен быть меньше 0',
            'work_experience.max' => 'Опыт работы не должен быть больше 100',
            'adopted_at.date' => 'Поле adopted_at должно быть корректной датой.',
            'adopted_at.before_or_equal' => 'Дата принятия должна быть сегодня или в прошлом.',
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
