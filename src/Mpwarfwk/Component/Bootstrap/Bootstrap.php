<?php

namespace Mpwarfwk\Component\Bootstrap;

use Mpwarfwk\Component\DiContainer\Container;
use Mpwarfwk\Component\Exception\InvalidArgumentException;
use Mpwarfwk\Component\Http\Request\Request;
use Mpwarfwk\Component\Http\Response\Response;

class Bootstrap
{
    private $container;
    private $router;
    private $debug_mode;

    public function __construct($servicesPath, $debug = false, $debugServicesPath = null)
    {
        $this->container = new Container($servicesPath);
        $this->debug_mode = $debug;

        if ($this->debug_mode) {
            if (!is_null($debugServicesPath)) {
                $this->container->addServicesFromYAML($debugServicesPath);
            }
        }

        $this->router = $this->container->get('router');
    }

    public function run(Request $request)
    {
        switch ($request->getMethod()) {
            case Request::METHOD_GET:
                $route = $this->router->get($request->getPathInfo());
                break;
            case Request::METHOD_POST:
                $route = $this->router->post($request->getPathInfo());
                break;
            case Request::METHOD_PUT:
                $route = $this->router->put($request->getPathInfo());
                break;
            case Request::METHOD_DELETE:
                $route = $this->router->delete($request->getPathInfo());
                break;
            default:
                throw new InvalidArgumentException("Error: method {$request->getMethod()} not supported");
        }
        if (is_null($route)) {
            return new Response($request->getHeader(), "Error: Url not found", Response::HTTP_NOT_FOUND);
        }
        $controller = new $route['controller']($this->container);
        $request->setParams($route['params']);
        return call_user_func_array(array($controller, $route['function']), array($request));
    }

    public function getTags($key = null)
    {
        return $this->container->getTags($key);
    }
}