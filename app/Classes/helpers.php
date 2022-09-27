<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('isDatabaseAvailable')) {

    /**
     * @return bool
     */
    function isDatabaseAvailable(): bool
    {
        $result = false;

        try {

            DB::getPdo();
            DB::table('users')->get();

            $result = true;

        } catch (Exception $exception) {

        }

        return $result;
    }

}
