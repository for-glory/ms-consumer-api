<?php declare(strict_types=1);

namespace App\Infrastructure\Broker\RabbitMQ\Consumer;

use App\Infrastructure\Broker\RabbitMQ\RabbitMQBroker;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

abstract class BaseConsumerAbstract
{
    private RabbitMQBroker $broker;
    private AMQPChannel $channel;
    private string|null $prefixConsumerTag = 'ms-consumer-api';

    // -- Overridable attributes --

    // Exchange
    protected string $exchangePrefix = 'ms_consumer';
    protected string $exchangeName = 'default';
    protected string $exchangeType = 'topic';

    // Queue
    protected string $queue = 'default';
    protected string $routingKey = '';
    protected string|null $consumerTag = '';
    protected bool|null $noLocal = false;
    protected bool|null $noAck = false;
    protected bool|null $exclusive = false;
    protected bool|null $nowait = false;
    protected int|null $ticket = null;
    protected \PhpAmqpLib\Wire\AMQPTable|array|null $arguments = [];

    // Messages
    protected bool $reQueueOnFail = false;

    public function __construct()
    {
        $this->declareBroker();
        $this->startChannel();
        $this->declareQueue();
        $this->configChannel();
    }

    protected function getExchangeName(): string
    {
        return sprintf('%s.%s', $this->exchangePrefix, $this->exchangeName);
    }

    private function declareBroker(): void
    {
        $this->broker = new RabbitMQBroker(
            config('queue.connections.rabbitmq.host'),
            (int) config('queue.connections.rabbitmq.port'),
            config('queue.connections.rabbitmq.user'),
            config('queue.connections.rabbitmq.password'),
            config('queue.connections.rabbitmq.vhost')
        );
    }

    private function startChannel(): void
    {
        $this->channel = $this->broker->getChannel();
    }

    private function configChannel(): void
    {
        $this->channel->exchange_declare(
            $this->getExchangeName(),
            $this->exchangeType,
            false,
            true,
            false
        );

        $this->channel->queue_bind(
            $this->queue,
            $this->getExchangeName(),
            $this->routingKey
        );
    }

    private function declareQueue(): void
    {
        $this->channel->queue_declare(
            $this->queue,
            false,
            true,
            false,
            false
        );
    }

    private function getConsumeTag(): string
    {
        $consumerTagEvent = $this->consumerTag ?? '?';

        return "{$this->prefixConsumerTag}-{$consumerTagEvent}";
    }

    protected function basicConsume(): void
    {
        $this->channel->basic_consume(
            $this->queue,
            $this->getConsumeTag(),
            $this->noLocal,
            $this->noAck,
            $this->exclusive,
            $this->nowait,
            fn (AMQPMessage $message) => $this->consumeCallback($message),
            $this->ticket,
            $this->arguments
        );
    }

    private function getMessageBody(?AMQPMessage $message): ?array
    {
        try {
            return json_decode($message->body, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable $th) {
            return null;
        }
    }

    /**
     * @throws \Throwable
     */
    private function consumeCallback(AMQPMessage $message): void
    {
        try {
            $this->fire($this->getMessageBody($message) ?? []);

            $message->ack();
        } catch (\Throwable $th) {
            $message->nack($this->reQueueOnFail);

            throw $th;
        }
    }

    /**
     * Wait and process all incoming messages in an endless loop,
     * until connection exception or manual stop using self::stopConsume()
     *
     * @throws \PhpAmqpLib\Exception\AMQPOutOfBoundsException
     * @throws \PhpAmqpLib\Exception\AMQPRuntimeException
     * @throws \PhpAmqpLib\Exception\AMQPConnectionClosedException
     * @throws \ErrorException
     * @since 3.2.0
     */
    public function consume(float $maximumPoll = 10.0): void
    {
        $this->channel->consume($maximumPoll);
    }

    abstract public function fire(array $body): void;
}
