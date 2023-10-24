<?php declare(strict_types=1);

namespace App\Enums;

enum BrokerEnum: string
{
    case USER_CREATED_EVENT = 'user_created_event';
    case USER_UPDATED_EVENT = 'user_updated_event';
}