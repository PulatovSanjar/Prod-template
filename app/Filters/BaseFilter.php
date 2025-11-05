<?php
declare(strict_types=1);

namespace App\Filters;

use App\Contracts\FilterContract;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseFilter implements FilterContract
{
    public string $fieldName;
    public string $operation;

    public function apply(Builder $query, mixed $value): Builder
    {
        return $query->when(
            is_array($value),
            fn ($query) => $query->whereIn($this->fieldName, $value),
            fn ($query) => $query->where($this->fieldName, $this->operation, $value)
        );
    }
}
