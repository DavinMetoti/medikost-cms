<?php

namespace App\Http\Requests\Apps\UoM;

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
            'name' => 'required|string|max:255|unique:unit_of_measurements,name',
            'abbreviation' => 'required|string|max:50|unique:unit_of_measurements,abbreviation',
            'type' => 'required|string|in:weight,length,volume,area,count,time,temperature,electrical,percentage,general',
            'is_active' => 'required|boolean',
        ];
    }
}
