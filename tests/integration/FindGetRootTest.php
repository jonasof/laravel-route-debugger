<?php

class FindGetRootTest extends PHPUnit\Framework\TestCase
{
    public $originalPath;
    public $laravelForTestsDir = ".laravels-for-test";

    public $laravelVersions = [
        '5.1' => 'routes.php:16',
        '5.2' => 'routes.php:16',
        '5.3' => 'web.php:16',
        '5.4' => 'web.php:16',
        '5.5' => 'web.php:16',
        '5.6' => 'web.php:16',
        '5.7' => 'web.php:16',
        '5.8' => 'web.php:16',
        '6' => 'web.php:16',
        '7' => 'web.php:18',
        '8' => 'web.php:18',
    ];

    public function setUp(): void
    {
        $this->originalPath = __DIR__ . '/../../';
    }

    public function testGetWorksForAllLaravel5Versions()
    {
        foreach ($this->laravelVersions as $laravelVersion => $assert) {
            chdir($this->originalPath);
            exec("composer create-project --prefer-dist laravel/laravel=$laravelVersion $this->laravelForTestsDir/$laravelVersion", $output, $return);

            chdir("$this->originalPath/$this->laravelForTestsDir/$laravelVersion");
            exec("composer install --ignore-platform-reqs --no-scripts");

            $this->assertContains($assert, shell_exec($this->originalPath . '/bin/laravel-route-debugger find GET /'));
        }
    }
}
