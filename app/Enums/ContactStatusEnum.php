<?php

namespace App\Enums;

enum ContactStatusEnum: string
{
    case Unread = 'Unread';
    case Read = 'Read';
    case FollowUp = 'Follow Up';
    case Ignored = 'Ignored';

     /**
     * Get the CSS class for the status.
     *
     * @return string
     */
    public function getColorClass(): string
    {
        return match ($this) {
            self::Unread => 'bg-primary text-white', // Blue background, white text
            self::Read => 'bg-success text-white', // Green background, white text
            self::FollowUp => 'bg-warning text-dark', // Yellow background, dark text
            self::Ignored => 'bg-danger text-white', // Light red background, dark red text
        };
    }
}
