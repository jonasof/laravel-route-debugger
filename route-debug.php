<?php

require './vendor/autoload.php';
$app = require_once './bootstrap/app.php';

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

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = new Illuminate\Http\Request();
$request->server->set('REQUEST_METHOD', $argv[1]);
$request->server->set('REQUEST_URI', $argv[2]);
$request->enableHttpMethodParameterOverride();

$app->instance('request', $request);
$app->instance('router', $app->make(DebuggerRouter::class));

$kernel->bootstrap();

$result = $app->router->publicFindRoute($request);

if ($result->route_file_info) {
    $file = $result->route_file_info['file'];
    $line = $result->route_file_info['line'];
    $controller = $result->route_file_info['args'][1][1] ?? '';

    echo "File: $file:$line \n";
    echo "Controller: $controller \n";
} else {
    echo "Cannot find route info \n";
}
