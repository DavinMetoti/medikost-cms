<?php

namespace App\Http\Requests\Apps\Warehouse;

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
            'name'        => 'required|string|max:255|unique:warehouses,name,' . $this->route('warehouse'),
            'code'        => 'required|string|max:100|unique:warehouses,code,' . $this->route('warehouse'),
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
            $warehouseId = $this->route('warehouse');

            if ($parentId && $parentId == $warehouseId) {
                $validator->errors()->add('parent_id', 'A warehouse cannot be its own parent.');
            }
        });
    }
}
