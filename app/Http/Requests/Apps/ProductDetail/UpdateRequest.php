<?php

namespace App\Http\Requests\Apps\ProductDetail;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('facilities') && is_string($this->facilities)) {
            $this->merge([
                'facilities' => json_decode($this->facilities, true),
            ]);
        }

        if ($this->has('images') && is_string($this->images)) {
            $this->merge([
                'images' => json_decode($this->images, true),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'room_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:kosong,isi',
            'available_rooms' => 'nullable|integer|min:0',
            'facilities' => 'nullable|array',
            'facilities.*.header' => 'required|string|max:255',
            'facilities.*.items' => 'array',
            'facilities.*.items.*' => 'string|max:255',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'string|max:2048',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
