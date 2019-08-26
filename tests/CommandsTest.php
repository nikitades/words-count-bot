<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommandsTest extends WebTestCase
{
    public function testSetToken()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

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
        $kernel = static::createKernel();
        $application = new Application($kernel);

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
