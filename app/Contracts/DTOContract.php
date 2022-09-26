<?php

namespace App\Contracts;

interface DTOContract
{
    public static function transform(array $data);

    public function toArray(): array;
}
