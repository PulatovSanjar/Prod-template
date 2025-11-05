<?php
declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Rules\Auth\HasVerifyEmailRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'email'     => ['required', 'exists:users,email', new HasVerifyEmailRule],
            'password'  => 'required|min:4|max:191',
        ];
    }
}
