<?php


namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class PostRequest extends FormRequest
{
    /**
     * Получить правила валидации для запроса.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:50',
        ];
    }

    /**
     * Обработка неудачной валидации.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     */
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
        ];
    }

    /**
     * Разрешить запрос.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}