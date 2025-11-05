<?php
declare(strict_types=1);

namespace App\Utilities;

use App\Contracts\FilterContract;

final class FilterManager
{
    /**
     * @var array
     */
    protected array $filters = [];

    /**
     * @param array $filters
     * @return void
     */
    public function registerFilters(array $filters): void
    {
        $this->filters = $filters;
    }

    /**
     * @return array<FilterContract>
     */
    public function getFilters(): array
    {
        return $this->filters;
    }
}
