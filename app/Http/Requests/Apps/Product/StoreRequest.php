<?php

namespace App\Http\Requests\Apps\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'sku'               => 'nullable|string|max:100|unique:products,sku',
            'barcode'           => 'nullable|string|max:100|unique:products,barcode',
            'category_id'       => 'required|exists:categories,id',
            'supplier_id'       => 'nullable|exists:suppliers,id',
            'tag'               => 'nullable|json',
            'description'       => 'nullable|string',
            'stock'             => 'required|integer|min:0',
            'stock_minimum'     => 'nullable|integer|min:0',
            'price'             => 'required|numeric|min:0',
            'unit_id'           => 'nullable|exists:unit_of_measurements,id',
            'location'          => 'nullable|string',
            'is_active'         => 'nullable|boolean',
            'images'            => 'nullable|json',
            'specifications'    => 'nullable|json',
            'is_published'      => 'nullable|boolean',
            'created_by'        => 'required|exists:users,id',
            'updated_by'        => 'required|exists:users,id',
        ];
    }


}
