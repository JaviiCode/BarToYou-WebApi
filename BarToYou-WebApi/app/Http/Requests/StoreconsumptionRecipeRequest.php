<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreconsumptionRecipeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $member = $this->user();
        // Verificar si el usuario tiene los permisos necesarios
        return $member->role->name === 'Administrador' || $member->role->name === 'Camarero';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'consumption_id' => 'required|exists:consumption,id',
            'ingredient_id' => 'required|exists:ingredient,id',
            'ingredient_amount' => 'required|numeric',
            'ingredient_unit' => 'required|string|max:20',
        ];
    }
}
