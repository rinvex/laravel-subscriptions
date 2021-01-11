<?php

if (!function_exists('jsonable')) {
    /**
     * Get jsonable column data type.
     *
     * @return string
     */
    function jsonable(): string
    {
        switch(\DB::connection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME)) {
            case 'pgsql':
                return 'jsonb';
            default:
            case 'mysql':
                $dbVersion    = \DB::connection()->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION);
                $isOldVersion = version_compare($dbVersion, '5.7.8', 'lt');
                return $isOldVersion ? 'text' : 'json';
        }

    }
}
