<?php

namespace Commands\Docker;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends SymfonyCommand
{
    /**
     * @var string
     */
    private const DOCKER_PATH = 'docker/';

    /**
     * @var string
     */
    private const DOCKER_FILE = 'restart-containers.sh';

    /**
     * @var string
     */
    private $project;

    /**
     * @var boolean
     */
    private $isLocal;

    /**
     * @var string
     */
    private $projectDirectory;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->projectDirectory = getcwd();

        parent::__construct();
    }

    /**
     * Starts a project.
     * 
     * Arguments:
     * - project::optional
     * > If a project is specfied, it reads all available projects from the the
     * config file and starts the project.
     * > If a project is not specified, it starts the container from the local
     * docker-folder.
     */
    protected function start(InputInterface $input, OutputInterface $output)
    {
        if ($this->project = $input->getArgument('project')) {
            $this->isLocal = false;
        } else {
            $this->isLocal = true;
            $this->project = $this->getProjectName();
        }

        if ($this->isLocal == true) {
            $output->writeln('Starting local project: ' . $this->project);
        } else {
            $output->writeln('Starting project: ' . $this->project);
        }

        try {
            $this->startProject($this->isLocal);
        } catch(\Exception $e) {
            $output->writeln($e->getMessage());
        }
    }

    /**
     * Returns the project name, if a local project is started
     *
     * @return string
     */
    private function getProjectName() : string
    {
        $curDir = explode(DIRECTORY_SEPARATOR, getcwd());

        return $curDir[count($curDir)-1];
    }

    /**
     * Starts the project.
     *
     * @return void
     */
    private function startProject()
    {
        $this->projectDirectory = $this->prepareDirectory();

        if (!file_exists($this->projectDirectory)) {
            throw new \Exception('Cannot find docker file.');
        }

        shell_exec($this->projectDirectory);
    }

    /**
     * Returns the directory where the project is located.
     * 
     * @return string $projectDirectory
     */
    private function prepareDirectory() : string
    {
        $projectDirectory = $this->projectDirectory . DIRECTORY_SEPARATOR;
        
        if (!$this->isLocal) {
            $projectDirectory .= $this->project . DIRECTORY_SEPARATOR;
        }
        
        $projectDirectory .= self::DOCKER_PATH . self::DOCKER_FILE;

        return $projectDirectory;
    }
}