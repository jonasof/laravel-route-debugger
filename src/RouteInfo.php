<?php

namespace LaravelRouteFinder;

use JsonSerializable;

class RouteInfo implements JsonSerializable
{
    public $controller;
    public $file;
    public $line;

    public function getfileAndLine(): string
    {
        return "$this->file:$this->line";
    }

    public function jsonSerialize()
    {
        return (object) [
            "controller" => $this->controller,
            "file" => $this->file,
            "line" => $this->line,
        ];
    }
}