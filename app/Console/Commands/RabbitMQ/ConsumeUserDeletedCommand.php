<?php

namespace App\Console\Commands\RabbitMQ;

use App\Enums\BrokerEnum;
use App\Infrastructure\Broker\RabbitMQ\Consumer\UserDeletedConsumer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ConsumeUserDeletedCommand extends Command
{
    protected string $eventName = BrokerEnum::USER_DELETED_EVENT->value;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consume:user-deleted';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume user deleted queue from rabbitMQ';

    /**
     * Execute the console command.
     */
    public function handle(UserDeletedConsumer $consumer)
    {
        $this->info("Starting the {$this->eventName} queue consume");

        try {
            $consumer->consume();
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
        }
    }
}
