<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreorderRequest extends FormRequest
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
            'member_id' => 'required|exists:members,id',
            'consumption_recipe_id' => 'required|exists:consumptionrecipe,id',
            'date_time' => 'required|date',
            'quantity' => 'required|integer',
            'status_id' => 'required|exists:orderstatus,id',
        ];
    }
}
