<?php

namespace Mpwarfwk\Component\Router;


use Mpwarfwk\Component\Profiler\Profiler;

class RouterProfiler implements Profiler
{
    private $addedRoutes;
    private $matchedRoute;

    public function addRoute(array $route) {
        $this->addedRoutes[] = $route;
    }

    public function setMatchedRoute($routeUri, array $matchedRoute)
    {
        $this->matchedRoute['uri'] = $routeUri;
        $this->matchedRoute['route'] = $matchedRoute;
    }

    public function displayInformation()
    {
        $output = "";
        foreach ($this->addedRoutes as $addedRoute) {
            $output .= '<b>Route added:</b> ' . implode(", ",$addedRoute) . '</br>';
        }
        $output .= '<b>Matched route:</b> URI' . $this->matchedRoute['uri'] .  ' ->  Route ' . implode(", ",$this->matchedRoute['route']) . '</br>';
    }
}