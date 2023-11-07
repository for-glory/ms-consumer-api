<?php declare(strict_types= 1);

namespace App\Infrastructure\Broker\RabbitMQ\Consumer;

use App\Enums\BrokerEnum;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserUpdatedConsumer extends BaseConsumerAbstract
{
    protected string $exchangePrefix = 'ms_ad';
    protected string $queue = BrokerEnum::USER_UPDATED_QUEUE->value;
    protected string $routingKey = BrokerEnum::USER_UPDATED_EVENT->value;
    protected ?string $consumerTag = BrokerEnum::USER_UPDATED_EVENT->value;

    public function __construct()
    {
        parent::__construct();
        $this->basicConsume();
    }

    public function fire(array $body): void
    {
        try {
            $user = User::find($body['id']);
            if (is_null($user)) {
                Log::error('User does not exists', $body);

                return;
            }

            $user->fill($body);
            $user->save();

            echo "[{$this->queue}] - Updated user with id {$body['id']}" . PHP_EOL;
        } catch (\Throwable $th) {
            Log::error('Could not update user based on event', [
                'class' => self::class,
                'event' => BrokerEnum::USER_UPDATED_EVENT->value,
                'consumerTag' => $this->consumerTag,
                'payload' => $body
            ]);
        }
    }
}