<?php

namespace App\Controller;

use App\BotWrapper;
use Psr\Log\LoggerInterface;
use Longman\TelegramBot\Telegram;
use App\Repository\SettingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Longman\TelegramBot\Exception\TelegramException;
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
    public function index(Request $request, BotWrapper $botWrapper)
    {
        if ($request->query->get('test')) {
            return $this->json([
                'status' => 'ok'
            ]);
        }
        
        try {
            $botWrapper->run();
        } catch (TelegramException $e) {
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        
        return $this->json([
            'status' => 'ok'
        ]);
    }
}
