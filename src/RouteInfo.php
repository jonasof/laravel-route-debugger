<?php

namespace LaravelRouteFinder;

use JsonSerializable;

class RouteInfo implements JsonSerializable
{
    public $file;
    public $line;

    public $controller;
    public $controller_file;
    public $controller_line;

    public function getfileAndLine(): string
    {
        return "$this->file:$this->line";
    }

    public function getControllerFileAndLine(): string
    {
        if ($this->controller_file && $this->controller_line) {
            return "$this->controller_file:$this->controller_line";
        }

        return "";
    }

    public function jsonSerialize()
    {
        return [
            "route" => [
                "file" => $this->file,
                "line" => $this->line,
            ],
            "controller" => [
                "action" => $this->controller,
                "file" => $this->controller_file,
                "line" => $this->controller_line
            ]
        ];
    }
}