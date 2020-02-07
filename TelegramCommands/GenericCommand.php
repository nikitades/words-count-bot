<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;

/**
 * Generic command
 */
class GenericCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'generic';

    /**
     * @var string
     */
    protected $description = 'Handles generic commands or is executed by default when a command is not found';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Command execute method
     *
     * @return ServerResponse
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        return Request::sendMessage([
            'parse_mode' => 'html',
            'chat_id' => $chat_id,
            'text' => "Unsupported command: <b>$message->getCommand()</b>"
        ]);
    }
}
