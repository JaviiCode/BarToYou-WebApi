<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoremembersRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'password' => 'required|string|max:255',
            'token' => 'nullable|string|max:255',
            'expiration_date_token' => 'nullable|date',
            'role_id' => 'required|exists:role,id',
        ];
    }
}
