<?php

namespace Mpwarfwk\Component\Router;


class Route
{
    private $method;
    private $uri;
    private $controller;
    private $function;

    private $pattern;

    public function __construct($method, $uri, $controller, $function)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->controller = $controller;
        $this->function = $function;

        $paramsRegex = preg_replace("/{(\w+)}/", "(\w+|\d)", $this->uri);
        $this->pattern = "~^" . $paramsRegex . "$~";
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getFunction()
    {
        return $this->function;
    }

    public function getInfo()
    {
        $info['controller'] = $this->getController();
        $info['function'] = $this->getFunction();
        return $info;
    }

    public function match($uri) {
        return preg_match($this->pattern,$uri);
    }

    public function getParameters($uri)
    {
        preg_match($this->pattern, $uri, $paramValues);
        array_shift($paramValues);

        preg_match_all("/{(\w+)}/", $this->uri, $paramNames);
        array_shift($paramNames);

        return array_combine($paramNames[0], $paramValues);
    }
}