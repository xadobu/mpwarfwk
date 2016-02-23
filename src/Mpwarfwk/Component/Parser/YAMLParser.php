<?php

namespace Mpwarfwk\Component\Parser;

use Symfony\Component\Yaml\Yaml;

class YAMLParser implements Parser
{

    public function parse($path, $key)
    {
        $ymlArray = Yaml::parse(file_get_contents($path));
        $routes = $ymlArray[$key];
        return $routes;
    }
}