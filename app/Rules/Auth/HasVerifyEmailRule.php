<?php
declare(strict_types=1);

namespace App\Rules\Auth;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;

class HasVerifyEmailRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $ok = User::query()
            ->where('email', (string) $value)
            ->whereNotNull('email_verified_at')
            ->exists();

        if (!$ok) {
            $fail(__('validation.email_unverified'));
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.email_unverified');
    }
}
