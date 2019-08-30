<?php

namespace App\Tests;

use App\Entity\Setting;
use Psr\Log\LoggerInterface;
use Longman\TelegramBot\Telegram;
use App\Repository\ChatRepository;
use App\Repository\WordRepository;
use App\Repository\SettingRepository;
use Longman\TelegramBot\Entities\Update;
use App\Repository\WordUsedTimesRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Longman\TelegramBot\Commands\SystemCommands\CountCommand;
use Longman\TelegramBot\Commands\SystemCommands\GenericmessageCommand;

class TelegramCommandsTest extends WebTestCase
{
    public function testCountCommand()
    {
        require_once(__DIR__ . "/../TelegramCommands/CountCommand.php");
        if (!defined('PHPUNIT_TESTSUITE')) define('PHPUNIT_TESTSUITE', true);
        global $kernel;
        self::bootKernel();
        $kernel = self::$kernel;
        $container = self::$kernel->getContainer();

        $wordUsedTimesRepo = $this->createMock(WordUsedTimesRepository::class);
        $wordUsedTimesRepo
            ->expects($this->any())
            ->method('findByWordsAndChatId')
            ->willReturn([]);

        $wordUsedTimesRepo
            ->expects($this->any())
            ->method('findBestByChatId')
            ->willReturn([]);
        $container->set(WordUsedTimesRepository::class, $wordUsedTimesRepo);

        $logger = $this->createMock(LoggerInterface::class);
        $container->set('logger.pub', $logger);

        $telegram = $this->client = new Telegram("123:testApiKey", "testBotName");

        //particular word
        $tgUpdate = new Update([
            'update_id' => 0,
            'message'   => [
                'message_id' => 0,
                'from'       => [
                    'id'         => 123,
                    'first_name' => "testBotName",
                    'username'   => "testUsername",
                ],
                'date'       => time(),
                'chat'       => [
                    'id'   => 321,
                    'type' => 'private',
                ],
                'text'       => "/count kek"
            ],
        ]);
        $countCommand = new CountCommand($telegram, $tgUpdate);
        $result = $countCommand->execute();
        $messageText = $result->getResult()->getText();
        $this->assertEquals($messageText, "<b>kek</b>: 0");

        //the best words
        $tgUpdate = new Update([
            'update_id' => 0,
            'message'   => [
                'message_id' => 0,
                'from'       => [
                    'id'         => 123,
                    'first_name' => "testBotName",
                    'username'   => "testUsername",
                ],
                'date'       => time(),
                'chat'       => [
                    'id'   => 321,
                    'type' => 'private',
                ],
                'text'       => "/count"
            ],
        ]);
        $countCommand = new CountCommand($telegram, $tgUpdate);
        $result = $countCommand->execute();
        $messageText = $result->getResult()->getText();
        $this->assertEquals($messageText, "<i>No statistics found</i>");
    }

    public function testGenericmessageCommand()
    {
        require_once(__DIR__ . "/../TelegramCommands/GenericmessageCommand.php");
        if (!defined('PHPUNIT_TESTSUITE')) define('PHPUNIT_TESTSUITE', true);
        global $kernel;
        self::bootKernel();
        $kernel = self::$kernel;
        $container = self::$kernel->getContainer();

        $chatRepo = $this->createMock(ChatRepository::class);
        $chatRepo
            ->expects($this->exactly(1))
            ->method('ensureChatIsSaved')
            ->willReturn(null);
        $container->set(ChatRepository::class, $chatRepo);

        $wordsRepo = $this->createMock(WordRepository::class);
        $wordsRepo
            ->expects($this->exactly(1))
            ->method('ensureWordsIDs')
            ->willReturn(null);
        $container->set(WordRepository::class, $wordsRepo);

        $wordUsedTimesRepo = $this->createMock(WordUsedTimesRepository::class);
        $wordUsedTimesRepo
            ->expects($this->exactly(1))
            ->method('massIncrementUsage')
            ->willReturn(null);
        $container->set(WordUsedTimesRepository::class, $wordUsedTimesRepo);

        $telegram = $this->client = new Telegram("123:testApiKey", "testBotName");

        //just some words from group
        $words = implode(" ", str_split(base64_encode(rand(1111111111, 99999999999)), 3));
        $tgUpdate = new Update([
            'update_id' => 0,
            'message'   => [
                'message_id' => 0,
                'from'       => [
                    'id'         => 123,
                    'first_name' => "testBotName",
                    'username'   => "testUsername",
                ],
                'date'       => time(),
                'chat'       => [
                    'id'   => 321,
                    'type' => 'group',
                    'title' => 'sraka'
                ],
                'text'       => $words
            ],
        ]);
        $genericMessageCommand = new GenericmessageCommand($telegram, $tgUpdate);
        $result = $genericMessageCommand->execute();
    }
}
