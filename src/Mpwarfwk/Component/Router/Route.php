<?php

namespace Mpwarfwk\Component\Router;


class Route
{
    private $uri;
    private $controller;
    private $method;

    public function __construct($uri, $controller, $method)
    {
        $this->uri = $uri;
        $this->controller = $controller;
        $this->method = $method;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getParameters($uri)
    {
        $paramsRegex = preg_replace("/{(\w+)}/", "(\w+|\d)", $this->uri);
        $regex = "~".$paramsRegex."~";
        preg_match($regex, $uri, $paramValues);
        array_shift($paramValues);

        preg_match_all("/{(\w+)}/", $this->uri, $paramNames);
        array_shift($paramNames);

        return array_combine($paramNames[0], $paramValues);
    }



}