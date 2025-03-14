<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateorderRequest extends FormRequest
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
            'member_id' => 'required|exists:members,id',
            'consumption_recipe_id' => 'required|exists:ConsumptionRecipe,id',
            'date_time' => 'required|date',
            'quantity' => 'required|integer',
            'status_id' => 'required|exists:OrderStatus,id',
        ];
    }
}
