<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use App\Entity\Word;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use App\Repository\ChatRepository;
use App\Repository\WordRepository;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Update;
use App\Repository\WordUsedTimesRepository;
use Longman\TelegramBot\Commands\SystemCommand;

/**
 * Help command
 *
 * Tells what to do
 */
class HelpCommand extends SystemCommand
{

    /**
     * Logger instance
     *
     * @var Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(Telegram $tg, Update $update)
    {
        parent::__construct($tg, $update);

        global $kernel;
        $this->logger = $kernel->getContainer()->get("logger.pub");
    }

    /**
     * @var string
     */
    protected $name = 'help';

    /**
     * @var string
     */
    protected $usage = '/help';

    /**
     * @var string
     */
    protected $description = 'Sends help message';

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
        $this->logger->debug("Help message executed");
        $chat_id = $this->getMessage()->getChat()->getId();
        $text = "Available commands:\n<b>/count</b> - shows three top popular words in this chat\n<b>/count {word}</b> - shows popularity for the given word";
        Request::sendMessage([
            'parse_mode' => 'html',
            'chat_id' => $chat_id,
            'text' => $text
        ]);
    }
}
