<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Commands\UserCommand;

/**
 * Generic message command
 *
 * Gets executed when any type of message is sent.
 */
class HowmuchCommand extends UserCommand
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
    protected $name = 'howmuch';

    /**
     * @var string
     */
    protected $description = 'Tell what most popular words are';

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
        $chat_id = $this->getMessage()->getChat()->id;
        $text = $this->getMessage()->getText(true);

        Request::sendMessage([
            'text' => 'huh!' . ($text ? " ({$text})" : ""),
            'chat_id' => $chat_id
        ]);

        $this->logger->debug("How much command executed");
        
        /**
         * TODO:
         *      - if no options given, tell three most used words by chat
         *      - if the word is given, tell it's used times count
         *
         */
    }
}
