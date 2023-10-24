<?php declare(strict_types=1);

namespace App\Infrastructure\Search\ElasticSearch\Enums;

enum OperationEnum : string
{
    case INDEX = 'index';
    case INDEX_CREATE = 'index_create';
    case SEARCH = 'search';
    case DELETE = 'delete';
}