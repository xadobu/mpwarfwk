<?php

namespace Mpwarfwk\Component\Router;

use Mpwarfwk\Component\Router\RouteParser\Parser;

class Router
{
    private $fields = ['uri', 'controller', 'method'];

    private $routes = [];
    private $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function loadRoutes($path)
    {
        $routesArray = $this->parser->parse($path);
        $routes = [];
        foreach ($routesArray as $rawRoute) {
            $keys = array_keys($rawRoute);

            if ($keys != $this->fields) {
                throw new InvalidArgumentException("Error: routes must contain only a uri, a controller and a method. Check your routes configuration file.");
            }

            if (array_key_exists($rawRoute['uri'], $routes)) {
                throw new DuplicatedRouteException("Error: route \"" . $rawRoute['uri'] . "\" already defined.");
            }

            $route = new Route($rawRoute['uri'], $rawRoute['controller'], $rawRoute['method']);
            $routes[$rawRoute['uri']] = $route;
        }
        $this->routes = $routes;
    }

    public function getRoute($route)
    {
        return $this->routes[$route];
    }
}