<?php

namespace LaravelRouteFinder\Core;

use ReflectionClass;

class ActionParser
{
    public $action;

    public $file;
    public $line;

    public function __construct(string $action)
    {
        $this->action = $action;

        $this->parseActionFileAndLine();
    }

    protected function parseActionFileAndLine()
    {
        if (!str_contains($this->action, "@")) {
            $this->file = "CLOSURE";
            return;
        }

        $controller_info = explode("@", $this->action);
        $controller = $controller_info[0];
        $method = $controller_info[1];

        $reflectorClass = new ReflectionClass($controller);
        $this->file = $reflectorClass->getFileName();

        $reflectorMethod = $reflectorClass->getMethod($method);
        $this->line = $reflectorMethod->getStartLine();
    }

    public function isClosure()
    {
        return $this->file === "CLOSURE";
    }
}
