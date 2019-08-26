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
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Commands\UserCommand;

/**
 * Generic message command
 *
 * Gets executed when any type of message is sent.
 */
class HowmuchCommand extends UserCommand
{
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

        Request::sendMessage([
            'text' => 'huh!',
            'chat_id' => $chat_id
        ]);
        
        /**
         * TODO:
         *      - if no options given, tell three most used words by chat
         *      - if the word is given, tell it's used times count
         * 
         */
    }
}
