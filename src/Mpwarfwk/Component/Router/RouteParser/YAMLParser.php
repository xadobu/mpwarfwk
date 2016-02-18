<?php

namespace Mpwarfwk\Component\Router\RouteParser;

use Symfony\Component\Yaml\Yaml;

class YAMLParser implements Parser
{

    public function parse($path)
    {
        $ymlArray = Yaml::parse(file_get_contents($path));

        $routes = $ymlArray['routes'];

        return $routes;
    }
}