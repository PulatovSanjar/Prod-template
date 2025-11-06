<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Contracts\DTOContract;
use App\Exceptions\RoleNotFound;
use App\Jobs\SendEmailVerificationJob;

class RegisterService
{
    /**
     * @param DTOContract $data
     * @return User
     */
    public function register(DTOContract $data): User
    {
        /** @var User $user */
        $user = User::query()->create($data->toArray());

        if (config('app.env') !== 'local') {
            $this->sendVerification($user);
        }

        return $user;
    }

    /**
     * @param string $token
     * @return bool
     */
    public function verifyEmail(string $token): bool
    {
        $user = User::query()
            ->where('email_verification_token', $token)
            ->firstOrFail(); // <- гарантирует User, не null

        return $user->forceFill(['email_verified_at' => now()])->save();
    }

    /**
     * @param User $user
     * @return void
     */
    protected function sendVerification(User $user): void
    {
        SendEmailVerificationJob::dispatch($user);
    }

    /**
     * @param User $user
     * @return void
     * @throws RoleNotFound
     */
    protected function assignDefaultRole(User $user): void
    {
        $role = Role::query()->where('key', 'customer')->first();

        if (!$role) {
            throw new RoleNotFound('Role with key = customer not found');
        }

        $user->roles()->attach($role);
    }

    /**
     * @param User $user
     * @return string
     */
    public static function getVerifyUrl(User $user): string
    {
        $params = [
            'token' => $user->email_verification_token,
        ];

        return config('front.email-verify-url') . '?' . http_build_query($params);
    }
}
