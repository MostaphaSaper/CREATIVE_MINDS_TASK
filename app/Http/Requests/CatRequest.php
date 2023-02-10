<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CatRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'=>'required|min:5|max:20',
            'type'=>'required',
        ];
    }

    public function messages()
{
    return [
        'name.required' => 'A name is required',
        'name.min' => 'A name must be 5 characters at least',
        'name.max' => 'A name must be less than 20 characters ',
        'type.required' => 'A type is required',
    ];
}
}
