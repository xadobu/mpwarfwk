<?php

namespace Mpwarfwk\Component\Router;

use Mpwarfwk\Component\Exception\DuplicatedEntryException;
use Mpwarfwk\Component\Exception\InvalidArgumentException;
use Mpwarfwk\Component\Parser\Parser;

class Router
{
    private $fields = ['method', 'uri', 'controller', 'function'];
    private $methods = ['get', 'post', 'put', 'delete'];

    private $routes = [];
    private $parser;
    private $routerProfiler;

    public function __construct(Parser $parser, $routesFile, RouterProfiler $routerProfiler)
    {
        $this->parser = $parser;
        $this->routerProfiler = $routerProfiler;
        $this->loadRoutes($routesFile);
    }

    public function loadRoutes($path)
    {
        $routesArray = $this->parser->parse($path,'routes');
        $routes = [];
        foreach ($routesArray as $rawRoute) {
            $keys = array_keys($rawRoute);

            if ($keys != $this->fields) {
                throw new InvalidArgumentException("Error: routes must contain only".implode(", ".$this->fields).". Check your routes configuration file.");
            }

            if (!in_array($rawRoute['method'], $this->methods)) {
                throw new InvalidArgumentException("Error: HTTP method \"" . $rawRoute['method'] . "\" not supported.");
            }

            if (array_key_exists($rawRoute['uri'], $routes)) {
                throw new DuplicatedEntryException("Error: route \"" . $rawRoute['uri'] . "\" already defined.");
            }

            $route = new Route($rawRoute['method'], $rawRoute['uri'], $rawRoute['controller'], $rawRoute['function']);
            $routes[$rawRoute['method']][$rawRoute['uri']] = $route;
            $this->routerProfiler->addRoute(array(
                'method' => $rawRoute['method'],
                'uri' => $rawRoute['uri'],
                'controller' => $rawRoute['controller'],
                'function' => $rawRoute['function']
                ));
        }
        $this->routes = $routes;
    }

    private function getRoute($routeUri, array $routesList)
    {
        $matched = null;
        if (is_null($routesList)) {
            return $matched;
        }

        foreach ($routesList as $route) {
            if ($route->match($routeUri)) {
                $matched = $route->getInfo();
                $matched['params'] = $route->getParameters($routeUri);
                break;
            }
        }

        $this->routerProfiler->setMatchedRoute($routeUri, $matched);

        return $matched;
    }

    public function get($route)
    {
        if (!array_key_exists("get", $this->routes)) {
            return null;
        }

        return $this->getRoute($route, $this->routes['get']);
    }

    public function post($route)
    {
        if (!array_key_exists("post", $this->routes)) {
            return null;
        }

        return $this->getRoute($route, $this->routes['post']);
    }

    public function put($route)
    {
        if (!array_key_exists("put", $this->routes)) {
            return null;
        }

        return $this->getRoute($route, $this->routes['put']);
    }

    public function delete($route)
    {
        if (!array_key_exists("delete", $this->routes)) {
            return null;
        }

        return $this->getRoute($route, $this->routes['delete']);
    }
}