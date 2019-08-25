<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BotSetTokenCommand extends Command
{
    protected static $defaultName = 'bot:set-token';

    protected function configure()
    {
        $this
            ->setDescription('Sets a secure telegram token for the bot')
            ->addArgument('token', InputArgument::REQUIRED, 'The token')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $token = $input->getArgument('token');

        $

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
