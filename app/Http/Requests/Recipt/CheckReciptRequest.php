<?php

namespace App\Http\Requests\Recipt;

use Illuminate\Foundation\Http\FormRequest;

class CheckReciptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'platform' => ['required','in:ios,android'],
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
