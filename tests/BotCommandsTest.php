<?php

namespace App\Tests;

use App\Entity\Setting;
use App\Repository\SettingRepository;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;

class BotCommandsTest extends WebTestCase
{
    public function testSetToken()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);
        $container = self::$kernel->getContainer();
        $settingRepository = $this->createMock(SettingRepository::class);
        $settingRepository->method('get')->willReturn(new Setting("token", "123"));
        $settingRepository->method('setOrCreate')->willReturn(new Setting("token", "123"));
        $settingRepository->method('delete')->willReturn(null);
        $container->set(SettingRepository::class, $settingRepository);

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
    }

    public function testFlushToken()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);
        $container = self::$kernel->getContainer();
        $settingRepository = $this->createMock(SettingRepository::class);
        $settingRepository
            ->expects($this->at(1))
            ->method('get')
            ->willReturn(new Setting("token", "123"));
        $settingRepository
            ->expects($this->at(2))
            ->method('get')
            ->willReturn(null);
        $settingRepository->method('setOrCreate')->willReturn(new Setting("token", "123"));
        $settingRepository->method('delete')->willReturn(null);
        $container->set(SettingRepository::class, $settingRepository);

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
    }
}
