<?php

namespace LaravelRouteFinder\Console;

use Illuminate\Console\Command;
use LaravelRouteFinder\Core\RouteFinder;
use LaravelRouteFinder\Core\RouteInfo;

class FindRouteCommand extends Command
{
    protected $signature = 'route-debugger:find 
                            {method : HTTP Method (GET, POST...)} 
                            {route : Route (/route/1/new)} 
                            {--json : Print result in JSON instead of human format}';

    protected $description = 'Find command, file and line of the respective route';

    protected RouteFinder $route_finder;

    public function __construct(RouteFinder $route_finder)
    {
        $this->route_finder = $route_finder;

        parent::__construct();
    }

    public function handle()
    {
        $method = $this->argument('method');
        $route_name = $this->argument('route');

        $route = $this->route_finder->findRoute($method, $route_name);

        if ($this->option('json')) {
            $this->echoJson($route);
        } else {
            $this->echoHuman($route);
        }

        return 0;
    }

    private function echoHuman(RouteInfo $route)
    {
        $route_file_and_line = $route->getfileAndLine();
        $controller_file_and_line = $route->getControllerFileAndLine();

        $this->info("");
        $this->info("<fg=green>Route File</>: <options=underscore>$route_file_and_line</>");
        $this->info("");
        $this->info("<fg=green>Controller</>: <fg=white>$route->controller</>");
        $this->info("<fg=green>Controller File</>: <fg=white>$controller_file_and_line</>");
        $this->info("");
    }

    private function echoJson(RouteInfo $route)
    {
        $this->info(json_encode($route));
    }
}
