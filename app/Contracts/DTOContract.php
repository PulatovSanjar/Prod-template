<?php

namespace App\Contracts;

interface DTOContract
{
    /**
     * @param array $data
     * @return mixed
     */
    public static function transform(array $data): DTOContract;

    /**
     * @return array
     */
    public function toArray(): array;
}
