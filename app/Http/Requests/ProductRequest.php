<?php

namespace App\Http\Requests;

use App\Product;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'price' => 'required|integer',
            'color' => 'required|string|unique:products,color,null,id,size,'.$this->get('size').',product_type_id,'.$this->get('product_type_id'),
            'size' => 'required|string',
            'product_type_id' => 'required|integer|exists:product_types,id'
        ];

        switch ($this->getMethod())
        {
            case 'POST':
                return $rules;
            case 'DELETE':
                return [
                    'product_id' => 'required|integer|exists:products,id'
                ];
        }
    }
}
