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
            'member_id' => 'sometimes|required|exists:Members,id',
            'consumption_recipe_id' => 'sometimes|nullable|exists:ConsumptionRecipe,id',
            'consumption_id' => 'sometimes|nullable|exists:Consumption,id',
            'date_time' => 'sometimes|required|date',
            'quantity' => 'sometimes|required|integer',
            'status_id' => 'sometimes|required|exists:OrderStatus,id',
        ];
    }
}
