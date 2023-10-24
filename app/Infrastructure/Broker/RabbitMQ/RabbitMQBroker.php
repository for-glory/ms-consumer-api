<?php declare(strict_types=1);

namespace App\Infrastructure\Broker\RabbitMQ;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQBroker
{
    protected AMQPStreamConnection $connection;
    protected AMQPChannel $channel;

    public function __construct(
        private string $host,
        private int $port,
        private string $username,
        private string $password,
        private string $vhost = '/',
    ) {
        $this->setConnection($host, $port, $username, $password, $vhost);
    }

    private function setConnection(
        string $host,
        int $port,
        string $username,
        string $password,
        string $vhost
    ): void {
        $this->connection =  new AMQPStreamConnection(
            $host,
            $port,
            $username,
            $password,
            $vhost
        );
    }

    public function __destruct()
    {
        $this->connection->close();
    }

    /**
     * Fetches a channel object identified by the numeric channel_id, or
     * create that object if it doesn't already exist.
     * 
     * @throws \PhpAmqpLib\Exception\AMQPOutOfBoundsException
     * @throws \PhpAmqpLib\Exception\AMQPRuntimeException
     * @throws \PhpAmqpLib\Exception\AMQPTimeoutException
     * @throws \PhpAmqpLib\Exception\AMQPConnectionClosedException
     */
    public function getChannel(int|null $channelId = null): AMQPChannel
    {
        return $this->connection->channel($channelId);
    }
}