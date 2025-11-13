<?php
declare(strict_types=1);

namespace App\Http\DTOs;

use Illuminate\Support\Str;
use App\Contracts\DTOContract;
use Illuminate\Support\Facades\Hash;

class RegisterUserDTO implements DTOContract
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string
     */
    protected string $email;

    /**
     * @var string
     */
    protected string $password;

    /**
     * @var string
     */
    protected string $email_verification_token;

    /**
     * @param array $data
     * @return RegisterUserDTO
     */
    public static function transform(array $data): self
    {
        $object = new self();

        $object->name = $data['name'];
        $object->email = $data['email'];
        $object->password = Hash::make($data['password']);
        $object->email_verification_token = Str::random();

        return $object;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name'      => $this->name,
            'email'     => $this->email,
            'password'  => $this->password,
            'email_verification_token' => $this->email_verification_token,
        ];
    }
}
