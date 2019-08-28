<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BotWebhookTest extends WebTestCase
{
    public function testWebhookListens()
    {
        $client = static::createClient();
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $urlGen = self::$container->get('router');
        $webhookUrl = $urlGen->generate('webhookHandler');
        $crawler = $client->request('GET', $webhookUrl."?test=true");

        $this->assertResponseIsSuccessful();
    }
}
