<?php

namespace App;

use Psr\Log\LoggerInterface;
use Longman\TelegramBot\Telegram;
use App\Repository\SettingRepository;
use PHPUnit\Runner\Exception;

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
        if (!$apiKey) {
            throw new \Exception("API Key is incorrect");
        }
        if (!$botName) {
            throw new \Exception("Bot Name is incorrect");
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
