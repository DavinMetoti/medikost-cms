<?php

namespace App\Http\Requests\Apps\Location;

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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'warehouse_id' => 'required|exists:warehouses,id',
            'parent_id' => 'nullable|exists:locations,id',
            'location_type_id' => 'required|exists:location_types,id',
            'code' => 'required|string|max:50|unique:locations,code,' . $this->route('location'),
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ];
    }
}
