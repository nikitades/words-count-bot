<?php

namespace App\Controller;

use App\BotWrapper;
use Psr\Log\LoggerInterface;
use Longman\TelegramBot\Telegram;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Longman\TelegramBot\Exception\TelegramException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BotController extends AbstractController
{

    /**
     * Logger
     *
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
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
            $result = $botWrapper->run();
        } catch (TelegramException $e) {
            $this->logger->error("Bot error: " . $e->getMessage());
            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        $this->logger->info("Bot was run");
        
        return $this->json([
            'status' => 'ok'
        ]);
    }
}
