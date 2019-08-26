<?php

namespace App\Command;

use App\Repository\SettingRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BotCheckTokenCommand extends Command
{
    protected static $defaultName = 'bot:check-token';
    /**
     * Setting repo
     *
     * @var SettingRepository
     */
    protected $sr;

    public function __construct (SettingRepository $sr)
    {
        parent::__construct();
        $this->sr = $sr;
    }

    protected function configure()
    {
        $this
            ->setDescription('Checks the token and show last 4 symbols if found');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        
        $currentToken = $this->sr->get('token');

        if ($currentToken) {
            $val = $currentToken->getValue();
            $subval = substr($val, -4, 4);
            $io->success('Current token: ...' . $subval);
        } else {
            $io->error('No token found!');
        }

    }
}
