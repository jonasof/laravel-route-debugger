<?php

namespace LaravelRouteFinder\Core;

class DebuggerRouter extends \Illuminate\Routing\Router
{
    /**
     * Make findRoute public :)
     */
    public function publicFindRoute(...$params)
    {
        return $this->findRoute(...$params);
    }

    /**
     * This override capures backtrace from routes to register the line of the
     * route definition.
     */
    protected function createRoute($methods, $uri, $action)
    {
        $backtrace = collect(debug_backtrace());

        if (!is_array($methods)) {
            $methods = [$methods];
        }

        $routeDefinitionCalledFile = $backtrace->search(
            function ($file) use ($methods) {
                if (!isset($file['class']) || !isset($file['function'])) {
                    return false;
                }

                if ($file['class'] !== \Illuminate\Routing\Router::class) {
                    return false;
                }

                $possibleMethods = array_map('strtolower', $methods);
                $possibleMethods[] = 'match';
                $possibleMethods[] = 'any';

                return in_array($file['function'], $possibleMethods);
            }
        );

        $callerInfo = $backtrace->get($routeDefinitionCalledFile + 1);

        $route = parent::createRoute($methods, $uri, $action);

        $route->route_file_info = $callerInfo;

        return $route;
    }
}
