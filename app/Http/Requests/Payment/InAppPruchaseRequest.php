<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class InAppPruchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'client_token' => ['required', 'exists:client_tokens,token'],
            'recipt' => ['required', 'regex:/[13579]$/']

        ];
    }

    public function messages()
    {
        return [
            'recipt.regex' => 'invalid recipt'
        ];
    }
}
