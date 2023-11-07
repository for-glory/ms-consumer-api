<?php declare(strict_types=1);

namespace App\Enums;

enum BrokerEnum: string
{
    case USER_CREATED_EVENT = 'user.created.event';
    case USER_UPDATED_EVENT = 'user.updated.event';
    case USER_DELETED_EVENT = 'user.deleted.event';

    case USER_CREATED_QUEUE = 'user_created_queue';
    case USER_UPDATED_QUEUE = 'user_updated_queue';
    case USER_DELETED_QUEUE = 'user_deleted_queue';
}