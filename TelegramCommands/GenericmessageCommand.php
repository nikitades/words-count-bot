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
use App\Repository\ChatRepository;
use App\Repository\WordRepository;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Update;
use App\Repository\WordUsedTimesRepository;
use Longman\TelegramBot\Commands\SystemCommand;

/**
 * Generic message command
 *
 * Gets executed when any type of message is sent.
 */
class GenericmessageCommand extends SystemCommand
{

    /**
     * Logger instance
     *
     * @var Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Chat repo
     *
     * @var ChatRepository
     */
    protected $cr;

    /**
     * Words repo
     *
     * @var WordRepository
     */
    protected $wr;

    /**
     * Words usage repo
     *
     * @var WordUsedTimesRepository
     */
    protected $wutr;

    public function __construct(Telegram $tg, Update $update)
    {
        parent::__construct($tg, $update);

        global $kernel;
        $this->logger = $kernel->getContainer()->get("logger.pub");
        $this->cr = $kernel->getContainer()->get("App\Repository\ChatRepository");
        $this->wr = $kernel->getContainer()->get("App\Repository\WordRepository");
        $this->wutr = $kernel->getContainer()->get("App\Repository\WordUsedTimesRepository");
    }

    /**
     * @var string
     */
    protected $name = 'genericmessage';

    /**
     * @var string
     */
    protected $description = 'Handle generic message';

    /**
     * @var string
     */
    protected $version = '1.1.0';

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
    public function execute(): void
    {
        $this->logger->debug("Generic message executed");

        $chatType = $this->getMessage()->getChat()->getType();

        if (!in_array($chatType, ['group', 'supergroup'])) {
            $this->logger->warning("Ignoring message from {$chatType}");
            return;
        }

        $tg_chat_id = $this->getMessage()->getChat()->getId();
        $tg_chat_title = $this->getMessage()->getChat()->getTitle();
        $text = $this->getMessage()->getText();
        $text = Word::escapeWord($text);
        $words = explode(" ", $text);

        $this->wr->ensureWordsIDs($words);
        $this->cr->ensureChatIsSaved($tg_chat_id, $tg_chat_title);
        $this->wutr->massIncrementUsage($words, $tg_chat_id);
    }
}
