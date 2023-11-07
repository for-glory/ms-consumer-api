<?php

namespace App\Console\Commands\RabbitMQ;

use App\Enums\BrokerEnum;
use App\Infrastructure\Broker\RabbitMQ\Consumer\UserUpdatedConsumer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ConsumeUserUpdatedCommand extends Command
{
    protected string $queueName = BrokerEnum::USER_UPDATED_QUEUE->value;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consume:user-updated';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume user updated queue from rabbitMQ';

    /**
     * Execute the console command.
     */
    public function handle(UserUpdatedConsumer $consumer)
    {
        $this->info("Starting the {$this->queueName} queue consume");

        try {
            $consumer->consume();
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
        }
    }
}
