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
            'products' => 'required',
            'country_code' => 'required|string'
        ];


        foreach($this->request->get('products') as $key => $val)
        {
            $rules['products.'.$key.'.id'] = 'required|integer|exists:products,id';
            $rules['products.'.$key.'.quantity'] = 'required|integer';
        }


        return $rules;
    }
}
