<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductTypeRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|unique:product_types,name',
            'is_active' => 'required|boolean'
        ];

        switch ($this->getMethod())
        {
            case 'POST':
                return $rules;
            case 'PUT':
                return [
                        'product_type_id' => 'required|integer|exists:product_types,id',
                        'name' => [
                            'required',
                            Rule::unique('product_types')->ignore($this->name, 'name')
                        ]
                    ] + $rules;
            case 'DELETE':
                return [
                    'product_type_id' => 'required|integer|exists:product_types,id'
                ];
        }
    }

    /**
     * our own validation messages
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'A name is required',
            'name.unique'  => 'This name is already taken'
        ];
    }
}
