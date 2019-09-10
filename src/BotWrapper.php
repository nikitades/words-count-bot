<?php

namespace App;

use Psr\Log\LoggerInterface;
use Longman\TelegramBot\Telegram;
use App\Repository\SettingRepository;

class BotWrapper
{
    /**
     * Telegram client instance
     *
     * @var Telegram
     */
    private $client;

    /**
     * Logger
     *
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(SettingRepository $sr, LoggerInterface $logger)
    {
        $apiKey = $sr->get('token');
        $botName = $sr->get('botname');
        if (!($apiKey && $botName)) {
            throw new \Exception("Both API key and Bot Name must be defined correctly (use bot:set-<...> to solve the issue)");
        }
        $this->client = new Telegram($apiKey->getValue(), $botName->getValue());
        $this->logger = $logger;
    }

    /**
     * Makes the bot interpret the incoming request object.
     * Must be called in the controller method assigned to be the webhook endpoint.
     *
     * @return void
     */
    public function run()
    {
        $this->client->addCommandsPath(__DIR__ . "/../TelegramCommands");
        return $this->client->handle();
    }
}
