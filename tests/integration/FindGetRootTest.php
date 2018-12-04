<?php

class FindGetRootTest extends PHPUnit\Framework\TestCase
{
    public $originalPath;

    public $laravelVersions = [
        '5.0' => 'routes.php:14',
        '5.1' => 'routes.php:16',
        '5.2' => 'routes.php:16',
        '5.3' => 'web.php:16',
        '5.4' => 'web.php:16',
        '5.5' => 'web.php:16',
        '5.6' => 'web.php:16',
        '5.7' => 'web.php:16'
    ];

    public function setUp()
    {
        $this->originalPath = __DIR__ . '/../../';
    }

    public function testGetWorksForAllLaravel5Versions()
    {
        foreach ($this->laravelVersions as $laravelVersion => $assert) {
            chdir($this->originalPath);

            exec("composer create-project --prefer-dist laravel/laravel=$laravelVersion tests_laravels/$laravelVersion", $output, $return);
            chdir($this->originalPath . "/tests_laravels/$laravelVersion");
            exec("composer install --ignore-platform-reqs --no-scripts");
            $this->assertContains($assert, shell_exec($this->originalPath . '/bin/laravel-route-debugger find GET /'));
        }
    }
}
