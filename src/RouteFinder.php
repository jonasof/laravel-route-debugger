<?php

namespace LaravelRouteFinder;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Exception;

class RouteFinder
{
    function __construct()
    {
        require './vendor/autoload.php';
        $this->app = require './bootstrap/app.php';
    }

    function findRoute (string $method, string $route) : RouteInfo
    {
        $request = $this->getFakeRequest($method, $route);
        $router = $this->getRouter($request);

        $result = $router->publicFindRoute($request);

        if (! $result->route_file_info) {
            throw new Exception("Cannot find route info for $method $route");
        }

        $route_info = new RouteInfo();

        $route_info->file = $result->route_file_info['file'];
        $route_info->line = $result->route_file_info['line'];
        $route_info->controller = $result->getAction()['controller'] ?? 'CLOSURE';

        $action_parser = new ActionParser($route_info->controller);
        $route_info->controller_file = $action_parser->file;
        $route_info->controller_line = $action_parser->line;

        return $route_info;
    }

    protected function getRouter (Request $request) : DebuggerRouter
    {
        $debug_router = $this->app->make(DebuggerRouter::class);

        $this->app->instance('request', $request);
        $this->app->instance('router', $debug_router);

        $kernel = $this->app->make(Kernel::class);
        $kernel->bootstrap();

        return $debug_router;
    }

    protected function getFakeRequest (string $method, string $route) : Request
    {
        $request = new Request();
        $request->server->set('REQUEST_METHOD', $method);
        $request->server->set('REQUEST_URI', $route);
        $request->enableHttpMethodParameterOverride();
        return $request;
    }
}
