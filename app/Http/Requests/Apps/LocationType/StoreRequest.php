<?php

namespace App\Http\Requests\Apps\LocationType;

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
            'name'        => 'required|string|max:255|unique:location_types,name',
            'code'        => 'required|string|max:100|unique:location_types,code',
            'level_order' => 'required|integer|min:1',
            'is_active'   => 'required|boolean',
        ];
    }
}
