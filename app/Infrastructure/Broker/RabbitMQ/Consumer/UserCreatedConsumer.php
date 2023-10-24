<?php declare(strict_types= 1);

namespace App\Infrastructure\Broker\RabbitMQ\Consumer;

use App\Enums\BrokerEnum;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserCreatedConsumer extends BaseConsumerAbstract
{
    protected string $queue = BrokerEnum::USER_CREATED_EVENT->value;
    protected ?string $consumerTag = BrokerEnum::USER_CREATED_EVENT->value;

    public function __construct()
    {
        parent::__construct();
        $this->basicConsume();
    }

    public function fire(array $body): void
    {
        try {
            $user = new User($body);
            $user->save();
        } catch (\Throwable $th) {
            Log::error('Could not create user based on event', [
                'class' => self::class,
                'event' => BrokerEnum::USER_CREATED_EVENT->value,
                'consumerTag' => $this->consumerTag,
                'payload' => $body,
            ]);
        }
    }
}