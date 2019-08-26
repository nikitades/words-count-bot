<?php

namespace App\Controller;

use Longman\TelegramBot\Telegram;
use App\Repository\SettingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BotController extends AbstractController
{

    /**
     * Setting repo
     *
     * @var SettingRepository
     */
    protected $sr;

    public function __construct(SettingRepository $sr)
    {
        $this->sr = $sr;
    }
    /**
     * @Route("/bot/handler", name="webhookHandler")
     */
    public function index(Request $request)
    {
        if ($request->query->get('test')) {
            return $this->json([
                'status' => 'ok'
            ]);
        }
        $botName = $this->sr->get('botname');
        if (!$botName) {
            throw new Exception("No predefined bot name found; run bot:set-name");
        }
        $apiKey = $this->sr->get('token')->getValue();
        if (!$botName) {
            throw new Exception("No API key found; run bot:set-token");
        }
        $client = new Telegram($apiKey, $botName);
        $client->handle();
        return $this->json([
            'status' => 'ok'
        ]);
    }
}
