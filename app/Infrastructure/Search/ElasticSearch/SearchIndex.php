<?php declare(strict_types= 1);

namespace App\Infrastructure\Search\ElasticSearch;

use App\Infrastructure\Search\ElasticSearch\Enums\OperationEnum;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ElasticsearchException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Exception;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class SearchIndex
{
    public function __construct(
        private string $indexName,
        private SearchClient $client
    ) { }

    /**
     * @param  array<string, string>  $params
     * @param  array<string, string>  $options
     * @param  array<string, string>|null  $paginate
     * 
     * @throws ElasticsearchException
     * @throws ServerResponseException
     * @throws Exception
     */
    public function search(array $params = [], array $options = []): array
    {
        $body = [];
        if (! empty($params)) {
            $body = [
                'query' => [
                    'match' => $params,
                ],
            ];
        }

        return $this->request(OperationEnum::SEARCH, array_merge(
            $options,
            [
                'index' => $this->indexName,
                'body'  => $body,
            ]
        ));
    }

    /**
     * @throws ElasticsearchException
     * @throws ServerResponseException
     * @throws Exception
     */
    public function createIndex(array $options = []): void
    {
        $this->request(OperationEnum::INDEX_CREATE, array_merge($options, [
            'index' => $this->indexName,
        ]));
    }

    /**
     * @throws ElasticsearchException
     * @throws ServerResponseException
     * @throws Exception
     */
    public function saveObject($object, array $options = []): void
    {
        $this->request(OperationEnum::INDEX, array_merge($options, [
            'index' => $this->indexName,
            'id' => $object['id'],
            'body' => $object,
        ]));
    }

    /**
     * @param  array<int, object>  $objects
     * @param  array<string, string>  $options
     * 
     * @throws ElasticsearchException
     * @throws ServerResponseException
     * @throws Exception
     */
    public function saveObjects(array $objects, array $options = []): void
    {
        foreach ($objects as $object) {
            $this->saveObject($object, $options);
        }
    }

    /**
     * @param  object  $objects
     * @param  array<string, string>  $options
     * 
     * @throws ElasticsearchException
     * @throws ServerResponseException
     * @throws Exception
     */
    public function updateObject($object, array $options = []): void
    {
        $this->request(OperationEnum::INDEX, array_merge($options, [
            'index' => $this->indexName,
            'id' => $object['id'],
            'body' => [
                'doc' => $object
            ],
        ]));
    }

    /**
     * @param  array<int, object>  $objects
     * @param  array<string, string>  $options
     * 
     * @throws ElasticsearchException
     * @throws ServerResponseException
     * @throws Exception
     */
    public function updateObjects(array $objects, array $options = []): void
    {
        foreach ($objects as $object) {
            $this->updateObject($object, $options);
        }
    }

    /**
     * @throws ElasticsearchException
     * @throws ServerResponseException
     * @throws Exception
     */
    public function clearObjects(array $options = []): array
    {
        return $this->request(OperationEnum::INDEX, array_merge([
            $options,
            'index' => $this->indexName,
            ['body' => []]
        ]));
    }

    public function deleteObjects(array $keys, array $options = []): void
    {
        foreach ($keys as $key) {
            $this->deleteObject($key, $options);
        }
    }

    public function deleteObject($objectKey, array $options = []): void
    {
        $this->delete(['id' => $objectKey]);
    }

    public function delete(array $options = []): void
    {
        $this->request(OperationEnum::DELETE, array_merge(
            $options,
            ['index' => $this->indexName]
        ));
    }

    /**
     * @throws ElasticsearchException
     * @throws ServerResponseException
     * @throws Exception
     */
    private function request(OperationEnum $operation, array $options = []): array
    {
        try {
            return $this->client->{$operation->value}($options) ?? [];
        } catch (ClientResponseException $exception) {
            $this->logResponseError($exception->getResponse());

            return [];
        } catch (ServerResponseException $exception) {
            $this->logResponseError($exception->getResponse());

            throw $exception;
        } catch (Exception $exception) {
            Log::error($exception->getMessage(), $exception->getTrace());

            throw $exception;
        }
    }

    private function logResponseError(ResponseInterface $response): void
    {
        Log::error("Error of type {$response->getStatusCode()}", [
            'status' => $response->getStatusCode(),
            'body' => $response->getBody()->getContents(),
        ]);
    }
}