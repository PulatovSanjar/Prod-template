<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface FilterContract
{
    public function apply(Builder $query, mixed $value): Builder;
}
