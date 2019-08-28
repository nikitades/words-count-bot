<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommandsTest extends WebTestCase
{
    public function testSetToken()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $container = self::$kernel->getContainer();
        $originalTokenSetting = $container->get("App\Repository\SettingRepository")->get('token');
        $originalToken = null;
        if (!empty($originalTokenSetting)) {
            $originalToken = $originalTokenSetting->getValue();
        }

        //TODO: Mock repositories

        $setCommand = $application->find('bot:set-token');
        $checkCommand = $application->find('bot:check-token');

        $setCommandTester = new CommandTester($setCommand);
        $checkCommandTester = new CommandTester($checkCommand);

        $setCommandTester->execute([
            'command' => $setCommand->getName(),
            'token' => '123',
        ]);

        $checkCommandTester->execute([
            'command' => $checkCommand->getName()
        ]);

        $output = $checkCommandTester->getDisplay();
        $this->assertContains('123', $output);

        if (!empty($originalToken)) {
            $container->get("App\Repository\SettingRepository")->setOrCreate('token', $originalToken);
        }
    }

    public function testFlushToken()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $container = self::$kernel->getContainer();
        $originalTokenSetting = $container->get("App\Repository\SettingRepository")->get('token');
        $originalToken = null;
        if (!empty($originalTokenSetting)) {
            $originalToken = $originalTokenSetting->getValue();
        }

        //TODO: Mock repositories

        $setCommand = $application->find('bot:set-token');
        $flushCommand = $application->find('bot:flush-token');
        $checkCommand = $application->find('bot:check-token');

        $setCommandTester = new CommandTester($setCommand);
        $flushCommandTester = new CommandTester($flushCommand);
        $checkCommandTester = new CommandTester($checkCommand);

        $setCommandTester->execute([
            'command' => $setCommand->getName(),
            'token' => '123',
        ]);

        $checkCommandTester->execute([
            'command' => $checkCommand->getName()
        ]);
        $firstCheckOutput = $checkCommandTester->getDisplay();
        $this->assertContains('123', $firstCheckOutput);

        $flushCommandTester->execute([
            'command' => $flushCommand->getName()
        ]);
        $flushOutput = $flushCommandTester->getDisplay();
        $this->assertContains('Token flushed', $flushOutput);

        $checkCommandTester->execute([
            'command' => $checkCommand->getName()
        ]);
        $secondCheckOutput = $checkCommandTester->getDisplay();
        $this->assertContains('No token found!', $secondCheckOutput);

        if (!empty($originalToken)) {
            $container->get("App\Repository\SettingRepository")->setOrCreate('token', $originalToken);
        }
    }
}
