<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use App\Entity\Word;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Entities\Update;
use App\Repository\WordUsedTimesRepository;
use Longman\TelegramBot\Commands\UserCommand;

/**
 * Unknown command
 *
 * A fail safe command
 */
class GenericCommand extends UserCommand
{
    /**
     * Logger instance
     *
     * @var Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Word used times repo
     *
     * @var WordUsedTimesRepository
     */
    protected $wutr;

    public function __construct(Telegram $tg, Update $update)
    {
        parent::__construct($tg, $update);

        global $kernel;
        $this->logger = $kernel->getContainer()->get("logger.pub");
        $this->wutr = $kernel->getContainer()->get("App\Repository\WordUsedTimesRepository");
    }

    /**
     * @var string
     */
    protected $name = 'generic';

    /**
     * @var string
     */
    protected $description = 'Unknown command';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * @var bool
     */
    protected $need_mysql = false;

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $this->logger->debug("Unknown command executed");
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        return Request::sendMessage([
            'parse_mode' => 'html',
            'chat_id' => $chat_id,
            'text' => "Unsupported command: <b>$message->getCommand()</b>"
        ]);
    }
}