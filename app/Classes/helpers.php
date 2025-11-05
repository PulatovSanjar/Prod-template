<?php
declare(strict_types=1);

use App\Models\Variable;
use Illuminate\Support\Facades\DB;

if (!function_exists('isDatabaseAvailable')) {

    /**
     * @return bool
     */
    function isDatabaseAvailable(string $table = 'users'): bool
    {
        $result = false;

        try {

            DB::connection()->getPdo();
            DB::table($table)->get();

            $result = true;

        } catch (Exception $exception) {

        }

        return $result;
    }

    if (!function_exists('validateLocales')) {

        /**
         * @param array $rules
         * @param array $locales
         * @return array
         */
        function validateLocales(array $rules, array $locales = []): array
        {
            $result = [];

            $locales = empty($locales) ? config('translatable.locales') : $locales;

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
         * @param string $key
         * @return mixed
         * @throws Exception
         */
        function getVariable(string $key): mixed
        {
            $variable = Variable::query()->where('key', $key)->first();

            if (!$variable) {
                throw new Exception('Variable with key "' . $key . '" not found');
            }

            return $variable->value;
        }

    }

}
