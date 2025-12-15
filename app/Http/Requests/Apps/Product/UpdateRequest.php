<?php

namespace App\Http\Requests\Apps\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $productId = $this->route('product'); // pastikan route model bindingnya benar

        return [
            'name'              => 'required|string|max:255|unique:products,name,' . $productId,
            'barcode'           => 'nullable|string|max:100|unique:products,barcode,' . $productId,
            'description'       => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'stock'             => 'required|integer|min:0',
            'stock_minimum'     => 'nullable|integer|min:0',
            'unit_id'           => 'nullable|exists:unit_of_measurements,id',
            'location'          => 'nullable|string',
            'category_id'       => 'required|exists:categories,id',
            'supplier_id'       => 'nullable|exists:suppliers,id',
            'tag'               => 'nullable|json',
            'images'            => 'nullable|json',
            'specifications'    => 'nullable|json',
            'is_published'      => 'nullable|boolean',
            'is_active'         => 'nullable|boolean',
            'updated_by'        => 'required|exists:users,id',
        ];
    }
}
