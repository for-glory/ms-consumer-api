<?php

namespace App\Console\Commands\RabbitMQ;

use App\Enums\BrokerEnum;
use App\Infrastructure\Broker\RabbitMQ\Consumer\UserCreatedConsumer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ConsumeUserCreatedCommand extends Command
{
    protected string $eventName = BrokerEnum::USER_CREATED_EVENT->value;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consume:user-created';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consume user created queue from rabbitMQ';

    /**
     * Execute the console command.
     */
    public function handle(UserCreatedConsumer $consumer)
    {
        $this->info("Starting the {$this->eventName} queue consume");

        $start = microtime(true);
        try {
            $consumer->consume();
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
        }
        $end = microtime(true);

        $diff = $end - $start;
        $diffInMs = round($diff * 1000,2);
        $this->info("{$diffInMs} ms");
    }
}
