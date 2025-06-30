<?php

namespace App\Enums;

enum ApplicationTypeEnum: int
{
    case IN_SIDE = 1;
    case OUT_SIDE = 2;

     /**
     * Get the CSS class for the status.
     *
     * @return string
     */
    public static function getString(self $status): string
    {
        return match ($status) {
            self::IN_SIDE => 'Job Specific',
            self::OUT_SIDE => 'General',
        };
    }
}
