<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateconsumptionRecipeRequest extends FormRequest
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
            'consumption_id' => 'required|exists:Consumption,id',
            'ingredient_id' => 'required|exists:Ingredient,id',
            'ingredient_amount' => 'required|numeric',
            'ingredient_unit' => 'required|string|max:20',
        ];
    }
}
