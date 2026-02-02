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
            'name' => 'required|string|max:255',
            'category' => 'required|in:Campur,Putri,Putra',
            'address' => 'nullable|string',
            'distance_to_kariadi' => 'nullable|numeric|min:0|max:999.99',
            'whatsapp' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'facilities' => 'nullable|json',
            'google_maps_link' => 'nullable|string|url',
            'is_active' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
            'images' => 'nullable|json',
            'created_by' => 'required|exists:users,id',
            'updated_by' => 'required|exists:users,id',
        ];
    }


}
