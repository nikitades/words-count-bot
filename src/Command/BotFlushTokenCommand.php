<?php

namespace App\Command;

use App\Repository\SettingRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BotFlushTokenCommand extends Command
{
    protected static $defaultName = 'bot:flush-token';

    /**
     * SettingRepository
     *
     * @var SettingRepository
     */
    protected $sr;

    public function __construct(SettingRepository $sr)
    {
        parent::__construct();
        $this->sr = $sr;
    }

    protected function configure()
    {
        $this
            ->setDescription('Removes the token');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $this->sr->delete('token');
        $io->warning('Token flushed');
    }
}
