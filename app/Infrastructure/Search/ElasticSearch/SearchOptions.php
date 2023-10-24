<?php declare(strict_types= 1);

namespace App\Infrastructure\Search\ElasticSearch;

class SearchOptions implements \ArrayAccess
{
    private array $options = [];

    private array $params = [];

    public function toSearchOptions(): array
    {
        return [
            'scroll' => $this['paginateTimer'] ?? '10m',
            'size' => $this['hitsPerPage'] ?? 50,
            'page' => $this['page'] ?? 1,
        ];
    }

    public function toSearchParams(): array
    {
        return [
            'scroll' => $this['paginateTimer'] ?? '10m',
            'size' => $this['hitsPerPage'] ?? 50,
            'page' => $this['page'] ?? 1,
        ];
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->options[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->options[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->options[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->options[$offset]);
    }
}