<?php

namespace App\Enums;

enum StatusLabelEnum: int
{
    case DEPARTMENT = 1;
    case STATUS = 2;
    case POSITION = 3;
     /**
     * Get the CSS class for the status.
     *
     * @return string
     */
    public static function getString(self $status): string
    {
        return match ($status) {
            self::DEPARTMENT => 'Department',
            self::STATUS => 'Status',
            self::POSITION => 'Position'
        };
    }
}
