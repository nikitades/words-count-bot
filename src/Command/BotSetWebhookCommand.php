<?php

namespace App\Command;

use Longman\TelegramBot\Telegram;
use App\Repository\SettingRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BotSetWebhookCommand extends Command
{
    protected static $defaultName = 'bot:set-webhook';

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

    protected function configure()
    {
        $this
            ->setDescription('Sets the webhook')
            ->addOption('botname', null, InputOption::VALUE_REQUIRED, 'The target bot\'s name')
            ->addArgument('webhook', InputArgument::REQUIRED, 'The webhook');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $wh = $input->getArgument('webhook');
        $apiKey = $this->sr->get();
        $botName = $input->getOption('botname');

        try {
            $client = new Telegram($apiKey, $botName);
            $result = $client->setWebhook($wh);
            if ($result->isOk()) {
                $io->success('SUCCESS: ' . $result->getDescription());
            } else {
                $io->warning('WARNING: ' . $result->getDescription());
            }
        } catch (Longman\TelegramBot\Exception\TelegramException $e) {
            $io->error('ERROR: ' . $e->getMessage());
        }
    }
}
