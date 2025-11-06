<?php
declare(strict_types=1);

namespace App\Contracts;

interface DTOContract
{
    /**
     * @param array $data
     */
    public static function transform(array $data): self;

    /**
     * @return array
     */
    public function toArray(): array;
}
