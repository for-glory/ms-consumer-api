<?php declare(strict_types=1);

namespace App\Infrastructure\Search\ElasticSearch;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;

class SearchClient
{
    private Client $client;

    public function __construct()
    {
        $this->instantiateClient();
    }

    private function instantiateClient(): void
    {
        $this->client = ClientBuilder::create()
            ->setHosts(config('scout.elasticsearch.config.hosts'))
            ->build();
    }

    public function index_create(array $params): void
    {
        $this->client->indices()->create($params);
    }

    public function index(array $params = []): void
    {
        $this->client->index($params);
    }

    public function search(array $params = []): array
    {
        return (array) $this->client->search($params)->asArray();
    }

    public function delete(array $params = []): void
    {
        $this->client->search($params)->asArray();
    }
}