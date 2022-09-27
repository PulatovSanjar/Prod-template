<?php

namespace App\Http\Requests\Admin\Roles;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $role = $this->role->id;

        return [
            'title'         => 'required|string|max:191|min:3',
            'key'           => 'required|string|unique:roles,key,' . $role . ',id',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'nullable|exists:permissions,id'
        ];
    }
}
