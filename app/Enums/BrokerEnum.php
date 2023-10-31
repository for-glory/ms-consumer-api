<?php declare(strict_types=1);

namespace App\Enums;

enum BrokerEnum: string
{
    case USER_CREATED_EVENT = 'user.created.event';
    case USER_UPDATED_EVENT = 'user.updated.event';
    case USER_DELETED_EVENT = 'user.deleted.event';
}