<?php

namespace LaravelRouteFinder;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class Command extends SymfonyCommand
{
    public $route_finder;

    public function __construct(mixed $name = null)
    {
        parent::__construct($name);

        $this->route_finder = new RouteFinder();
    }

    protected function configure()
    {
        $this
            ->setName('find')
            ->setDescription('Find file and line of a defined route.')
            ->addArgument('method', InputArgument::REQUIRED, 'HTTP Method (GET, POST..)')
            ->addArgument('route', InputArgument::REQUIRED, 'Route (/route/1/new')
            ->addOption(
                'json',
                'null',
                InputOption::VALUE_NONE,
                'Print result in JSON instead of human format'
            );
    }

    function execute(InputInterface $input, OutputInterface $output)
    {
        $method =  $input->getArgument('method');
        $route_name =  $input->getArgument('route');

        $route = $this->route_finder->findRoute($method, $route_name);

        if ($input->getOption('json')) {
            $this->echoJson($route, $output);
        } else {
            $this->echoHuman($route, $output);
        }
    }

    function echoHuman(RouteInfo $route, $output)
    {
        $route_file_and_line = $route->getfileAndLine();
        $controller = is_callable($route->controller) ? "Closure" : $route->controller;

        $output->writeln("File: $route_file_and_line");
        $output->writeln("Controller: $controller");
    }

    function echoJson(RouteInfo $route, $output)
    {
        $output->writeln(json_encode($route));
    }
}
