<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use App\Entity\Word;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Update;
use App\Repository\WordUsedTimesRepository;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;

/**
 * Greets a man
 */
class StartCommand extends UserCommand
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
    }

    /**
     * @var string
     */
    protected $name = 'start';

    /**
     * @var string
     */
    protected $description = 'Greets the user';

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
        $this->logger->debug("Start command executed");
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        return Request::sendMessage([
            'parse_mode' => 'html',
            'chat_id' => $chat_id,
            'text' => "Usage: /count <your word>"
        ]);
    }
}
