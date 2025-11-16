<?php
declare(strict_types=1);

use App\Models\Variable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

if (!function_exists('isDatabaseAvailable')) {
    function isDatabaseAvailable(?string $table = null): bool
    {
        try {
            // Есть ли соединение?
            DB::connection()->getPdo();

            // Если таблица указана — проверяем наличие
            return !$table || Schema::hasTable($table);
        } catch (Throwable $e) {
            return false;
        }
    }
}

if (!function_exists('validateLocales')) {
    function validateLocales(array $rules, array $locales = []): array
    {
        $result = [];
        $locales = empty($locales) ? (array) config('translatable.locales') : $locales;

        foreach ($locales as $locale) {
            foreach ($rules as $field => $rule) {
                $result[$locale . '.' . $field] = $rule;
            }
        }

        return $result;
    }
}

if (!function_exists('getVariable')) {

    /**
     * @throws RuntimeException
     */
    function getVariable(string $key): mixed
    {
        $variable = Variable::query()->where('key', $key)->first();

        if (!$variable) {
            throw new RuntimeException('Variable with key "' . $key . '" not found');
        }

        return $variable->value;
    }
}
