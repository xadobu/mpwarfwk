<?php

namespace Mpwarfwk\Component\Router;

use Mpwarfwk\Component\Router\RouteParser\Parser;

class Router
{
    private $fields = ['method', 'uri', 'controller', 'function'];
    private $methods = ['get', 'post', 'put', 'delete'];

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
                throw new InvalidArgumentException("Error: routes must contain only a http method, a uri, a controller and a function. Check your routes configuration file.");
            }

            if (!in_array($rawRoute['method'], $this->methods)) {
                throw new InvalidArgumentException("Error: HTTP method \"" . $rawRoute['method'] . "\" not supported.");
            }

            if (array_key_exists($rawRoute['uri'], $routes)) {
                throw new DuplicatedRouteException("Error: route \"" . $rawRoute['uri'] . "\" already defined.");
            }

            $route = new Route($rawRoute['method'], $rawRoute['uri'], $rawRoute['controller'], $rawRoute['method']);
            $routes[$rawRoute['method']][$rawRoute['uri']] = $route;
        }
        $this->routes = $routes;
    }

    public function get($route)
    {
        return $this->routes['get'][$route];
    }

    public function post($route)
    {
        return $this->routes['post'][$route];
    }

    public function put($route)
    {
        return $this->routes['put'][$route];
    }

    public function delete($route)
    {
        return $this->routes['delete'][$route];
    }
}