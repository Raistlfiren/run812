<?php

namespace App\Command;

use App\Service\RouteHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RouteCommand extends Command
{
    protected static $defaultName = 'routes:download';
    private EntityManagerInterface $entityManager;
    private RouteHandler $client;

    public function __construct(EntityManagerInterface $entityManager, RouteHandler $client)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
        $this->client = $client;
    }

    protected function configure()
    {
        $this
            ->setDescription('Parses a route and adds metadata into the database')
            ->setHelp('This command allows you to create database records')
            ->addArgument('json', InputArgument::OPTIONAL, 'The specified JSON file')
            ->addOption('name', null, InputOption::VALUE_OPTIONAL, 'Route name', '')
            ->addOption('distance', null, InputOption::VALUE_OPTIONAL, 'Route distance', '')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Starting route creator.');

        $this->client->fetchRoutes();

        return Command::SUCCESS;
    }
}