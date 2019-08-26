<?php

namespace App\Command;

use App\Repository\SettingRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BotSetNameCommand extends Command
{
    protected static $defaultName = 'bot:set-name';
    
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
            ->setDescription('Sets the bot name')
            ->addArgument('name', InputArgument::REQUIRED, 'Bot\'s name');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');

        $this->sr->setOrCreate('botname', $name);

        //прочекать что все переменные в порядке
        
        $io->success('Name successfully set: ' . $name);
    }
}
