<?php declare(strict_types= 1);

namespace App\Infrastructure\Search\ElasticSearch;

class ElasticSearch
{
    public function __construct(private SearchClient $client)
    { }

    public function initIndex(string $index): SearchIndex
    {
        return new SearchIndex($index, $this->client);
    }
}