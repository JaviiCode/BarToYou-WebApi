<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreorderStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $member = $this->user();
        // Verificar si el usuario tiene los permisos necesarios
        return $member->role->name === 'Administrador';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
        ];
    }
}
