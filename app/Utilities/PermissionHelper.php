<?php

namespace App\Utilities;

class PermissionHelper
{
    /**
     * @var array|string[]
     */
    protected static array $default = [
        'access',
        'create',
        'edit',
        'delete'
    ];

    /**
     * @var string
     */
    protected static string $delimiter = '_';

    /**
     * @param string $title
     * @return array
     */
    public static function make(string $title): array
    {
        $permissions = [];

        foreach (self::$default as $item) {
            $permissions[] = $title . self::$delimiter . $item;
        }

        return $permissions;
    }
}
