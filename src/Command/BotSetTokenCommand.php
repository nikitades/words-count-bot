<?php

namespace App\Command;

use App\Repository\SettingRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BotSetTokenCommand extends Command
{
    protected static $defaultName = 'bot:set-token';
    
    /**
     * Setting repo
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
            ->setDescription('Sets a secure telegram token')
            ->addArgument('token', InputArgument::REQUIRED, 'The token')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $token = $input->getArgument('token');
        $this->sr->setOrCreate('token', $token);
        $io->success('Token successfully set');
    }
}
