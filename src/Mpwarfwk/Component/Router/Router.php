<?php

namespace Mpwarfwk\Component\Router;

use Mpwarfwk\Component\Router\RouteParser\Parser;

class Router
{
    private $fields = ['method', 'uri', 'controller', 'function'];
    private $methods = ['get', 'post', 'put', 'delete'];

    private $routes = [];
    private $parser;

    public function __construct(Parser $parser, $routesFile)
    {
        $this->parser = $parser;
        $this->loadRoutes($routesFile);
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

            $route = new Route($rawRoute['method'], $rawRoute['uri'], $rawRoute['controller'], $rawRoute['function']);
            $routes[$rawRoute['method']][$rawRoute['uri']] = $route;
        }
        $this->routes = $routes;
    }

    private function getRoute($routeUri, array $routesList)
    {
        $matched = null;  // TODO error 404
        if (is_null($routesList)) {
            return $matched;
        }

        foreach ($routesList as $route) {
            if ($route->match($routeUri)) {
                $matched = $route->getInfo();
                break;
            }
        }
        return $matched;
    }

    public function get($route)
    {
        if (!array_key_exists("get", $this->routes)) {
            return null;  // TODO error 404
        }

        return $this->getRoute($route, $this->routes['get']);
    }

    public function post($route)
    {
        if (!array_key_exists("post", $this->routes)) {
            return null;  // TODO error 404
        }

        return $this->getRoute($route, $this->routes['post']);
    }

    public function put($route)
    {
        if (!array_key_exists("put", $this->routes)) {
            return null;  // TODO error 404
        }

        return $this->getRoute($route, $this->routes['put']);
    }

    public function delete($route)
    {
        if (!array_key_exists("delete", $this->routes)) {
            return null;  // TODO error 404
        }

        return $this->getRoute($route, $this->routes['delete']);
    }
}