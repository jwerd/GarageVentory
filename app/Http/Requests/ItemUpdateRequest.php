<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemUpdateRequest extends FormRequest
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
            'name'       => 'required|max:255',
            'description'=> 'string|nullable',
            'qty'        => 'integer',
            'price'      => 'numeric|nullable',
            'list_price' => 'numeric|nullable',
            'price_sold' => 'numeric|nullable',
            'image'      => 'nullable',
            'dimension'  => 'nullable'
        ];
    }
}
