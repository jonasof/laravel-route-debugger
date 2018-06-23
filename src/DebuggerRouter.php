<?php

namespace LaravelRouteFinder;

class DebuggerRouter extends \Illuminate\Routing\Router
{
    /**
     * @TODO autodetect imported route files
     */
    public $route_files = [
        "routes/api.php",
        "routes/console.php",
        "routes/web.php",
        "Http/routes.php"
    ];

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
        $fileinfo = collect(debug_backtrace())->filter(function ($file) {
            if (! isset($file['file'])) {
                return false;
            }

            foreach ($this->route_files as $route_file) {
                if (str_contains($file['file'], $route_file)) {
                    return true;
                }
            }

            return false;
        })->first();

        $route = parent::createRoute($methods, $uri, $action);

        $route->route_file_info = $fileinfo;

        return $route;
    }
}