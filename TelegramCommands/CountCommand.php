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

use App\Entity\Word;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Update;
use App\Repository\WordUsedTimesRepository;
use Longman\TelegramBot\Commands\UserCommand;

/**
 * Count the word command
 *
 * Counts the times one (or many) word was used
 */
class CountCommand extends UserCommand
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
    protected $name = 'count';

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
        $this->logger->debug("Count command executed");
        $chat_id = $this->getMessage()->getChat()->id;
        $text = $this->getMessage()->getText(true);

        if ($text) {
            return $this->getParticularWordsCount($text, $chat_id);
        }
        return $this->getTopThreeWordsCount($chat_id);
    }

    private function getParticularWordsCount(string $text, int $chat_id): void
    {
        $text = Word::escapeWord($text);
        $words = explode(" ", $text);
        $wordsCount = $this->wutr->findByWordsAndChatId($words, $chat_id);
        $resposeText = [];
        foreach ($words as $word) {
            $usedTimes = array_filter($wordsCount, function ($wcItem) use ($word) {
                return $wcItem->getWord()->getText() == $word;
            });
            if (count($usedTimes)) {
                $wcItem = array_shift($usedTimes);
                $resposeText[] = "<b>{$word}</b>: " . $wcItem->getUsedTimes();
            } else {
                $resposeText[] = "<b>{$word}</b>: 0";
            }
        }
        
        if (empty($resposeText)) {
            Request::sendMessage([
                'parse_mode' => 'html',
                'chat_id' => $chat_id,
                'text' => "<b>{$text}</b>: 0"
            ]);
            return;    
        }

        Request::sendMessage([
            'parse_mode' => 'html',
            'chat_id' => $chat_id,
            'text' => implode("\n", $resposeText)
        ]);
    }

    private function getTopThreeWordsCount(int $chat_id): void
    {
        $wordsCount = $this->wutr->findBestByChatId($chat_id);
        $resposeText = [];

        if (empty($wordsCount)) {
            Request::sendMessage([
                'parse_mode' => 'html',
                'chat_id' => $chat_id,
                'text' => "<i>No statistics found</i>"
            ]);
            return;
        }

        foreach ($wordsCount as $wcItem) {
            $resposeText[] = "<b>{$wcItem->getWord()->getText()}</b>: " . $wcItem->getUsedTimes();
        }

        Request::sendMessage([
            'parse_mode' => 'html',
            'chat_id' => $chat_id,
            'text' => implode("\n", $resposeText)
        ]);
    }
}
