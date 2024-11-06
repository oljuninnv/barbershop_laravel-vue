<?php

namespace App\Rules;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Validator;

class PhoneFormatRule implements Rule
{
    protected $message;

    /**
     * Create a new rule instance.
     *
     * @param string $message
     */
    public function __construct($message = null)
    {
        $this->message = $message;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $phone = preg_replace('/\(|\)|-|\+|\s/', '', $value);

        return is_numeric($phone) && strlen((string)$phone) === 11;
    }

    public static function handle() : string
    {
        return 'phone';
    }

    public function validate(string $attribute, $value, $params, Validator $validator) : bool
    {
        $handle = $this->handle();

        $validator->setCustomMessages([
            $handle => $this->message(),
        ]);

        return $this->passes($attribute, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message ?? 'Phone format of :attribute is wrong';
    }
}