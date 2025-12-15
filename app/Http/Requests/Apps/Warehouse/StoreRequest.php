<?php

namespace App\Http\Requests\Apps\Warehouse;

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
            'name'        => 'required|string|max:255|unique:warehouses,name',
            'code'        => 'required|string|max:100|unique:warehouses,code',
            'address'     => 'nullable|string',
            'description' => 'nullable|string',
            'parent_id'   => 'nullable|exists:warehouses,id',
            'is_active'   => 'required|boolean',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $parentId = $this->input('parent_id');
            if ($parentId) {
                // Check if trying to set parent to itself (though this shouldn't happen on create)
                // But we can add additional validation here if needed
            }
        });
    }
}
