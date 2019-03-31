<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'products' => 'required|array',
            'country_code' => 'required|string'
        ];

        switch ($this->getMethod())
        {
            case 'POST':
                return $rules;
            case 'DELETE':
                return [
                    'order_id' => 'required|integer|exists:orders,id'
                ];
        }
    }
}
