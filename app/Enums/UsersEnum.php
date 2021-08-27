<?php

namespace App\Enums;

use Illuminate\Support\Collection;

/**
 * Class UsersEnum
 * @package App\Enums
 */
class UsersEnum
{
    public const STATUS_DEFAULT = 0;
    public const STATUS_ACCESS = 1;
    public const STATUS_REJECT = 2;
    public const STATUS_DELETE = 3;

    public static function listStatuses(): array
    {
        return [
            self::STATUS_DEFAULT => __('Default'),
            self::STATUS_ACCESS => __('Access'),
            self::STATUS_REJECT => __('Reject'),
            self::STATUS_DELETE => __('Delete'),
        ];
    }

    public static function getNameByStatus($status){
        $statuses = self::listStatuses();

        return $statuses[$status];
    }
}
