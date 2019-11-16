<?php

namespace Commands\Docker;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Commands\Docker\Command;

class DockerCommand extends Command
{
    public function configure()
    {
        $this->setName('start')
            ->setDescription('This command starts the container.')
            ->setHelp('This command is just a example.')
            ->addArgument('project', InputArgument::OPTIONAL, 'Start a specific project.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->start($input, $output);
    }
}