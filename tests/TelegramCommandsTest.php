<?php

namespace App\Tests;

use App\Entity\Setting;
use Longman\TelegramBot\Telegram;
use App\Repository\SettingRepository;
use Longman\TelegramBot\Entities\Update;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Longman\TelegramBot\Commands\SystemCommands\CountCommand;

class TelegramCommandsTest extends WebTestCase
{
    public function testCountCommand()
    {
        require_once(__DIR__ . "/../TelegramCommands/CountCommand.php");
        define('PHPUNIT_TESTSUITE', true);
        global $kernel;
        self::bootKernel();
        $kernel = self::$kernel;
        $container = self::$kernel->getContainer();
        //TODO: mock WordUsedTimesRepository

        $telegram = $this->client = new Telegram("123:testApiKey", "testBotName");
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
                'text'       => "kek"
            ],
        ]);
        $countCommand = new CountCommand($telegram, $tgUpdate);
        $result = $countCommand->execute();
        $messageText = $result->getResult()->getText();
        $this->assertEquals($messageText, "<b>kek</b>: 0");
    }
}
