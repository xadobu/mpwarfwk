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
        $output .= "<b>Matched route:</b> <b>URI:</b> {$this->matchedRoute['uri']} ->  <b>Route:</b> {$this->matchedRoute['route']['controller']} <b>Action:</b> {$this->matchedRoute['route']['function']} <b>Params:</b> {$this->formatParams($this->matchedRoute['route']['params'])}</br>";
        return $output;
    }

    private function formatParams($array, $separator = ': ', $glue = ', ')
    {
        $keys = array_keys($array);
        $values = array_values($array);

        $newArray = [];
        for ($i = 0; $i < count($keys); $i++) {
            $newArray[] = $keys[$i] . $separator . $values[$i];
        }

        return implode($glue, $newArray);
    }
}