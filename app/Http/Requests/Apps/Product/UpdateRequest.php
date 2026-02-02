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
            'name' => 'required|string|max:255|unique:products,name,' . $productId,
            'category' => 'required|in:Campur,Putri,Putra',
            'address' => 'nullable|string',
            'distance_to_kariadi' => 'nullable|numeric|min:0|max:999.99',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'whatsapp' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'facilities' => 'nullable|json',
            'google_maps_link' => 'nullable|string|url',
            'is_active' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
            'images' => 'nullable|json',
            'removed_images' => 'nullable|json',
            'updated_by' => 'required|exists:users,id',
        ];
    }
}
