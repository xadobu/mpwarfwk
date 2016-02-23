<?php

namespace Mpwarfwk\Component\DiContainer;

use Mpwarfwk\Component\Exception\DuplicatedEntryException;
use Mpwarfwk\Component\Exception\InvalidArgumentException;
use Mpwarfwk\Component\Parser\YAMLParser;
use ReflectionClass;

class Container
{
    private $fields = ['name', 'class', 'arguments', 'tags', 'public', 'singleton'];
    private $parser;
    private $services = [];
    private $tags = [];

    public function __construct($path)
    {
        $this->parser = new YAMLParser();
        $this->addServicesFromYAML($path,true);
    }

    public function addServicesFromYAML($path, $strict = false) {
        $servicesArray = $this->parser->parse($path, 'services');

        $services = [];
        foreach ($servicesArray as $rawService) {

            $keys = array_keys($rawService);

            if ($keys != $this->fields) {
                throw new InvalidArgumentException("Error: services must contain only the following keys: " . implode(", ", $this->fields) . ". Check your services configuration file.");
            }

            if ($strict) {
                if (array_key_exists($rawService['name'], $services)) {
                    throw new DuplicatedEntryException("Error: service \"" . $rawService['name'] . "\" already defined.");
                }
            }

            if (is_array($rawService['tags'])) {
                foreach ($rawService['tags'] as $tag) {
                    $key = array_keys($tag)[0];
                    $this->tags[$key][] = $rawService['name'];
                }
            }

            $services[$rawService['name']] = [
                'name' => $rawService['name'],
                'class' => $rawService['class'],
                'arguments' => $rawService['arguments'],
                'public' => $rawService['public'],
                'singleton' => $rawService['singleton']
            ];
        }
        $this->services = $services;
    }

    public function get($serviceName)
    {
        if (!array_key_exists($serviceName, $this->services)) {
            throw new InvalidArgumentException("Error: service " . $serviceName . " not defined.");
        }

        $service = $this->services[$serviceName];
        if ($service['public']) {
            if ($service['singleton']) {
                return $this->serviceInstance($service);
            } else {
                return $this->serviceFactory($service);
            }
        }
        throw new InvalidArgumentException("Error: service " . $serviceName . " is not public.");
    }

    private function serviceInstance($service)
    {
        return $this->serviceFactory($service);
    }

    private function serviceFactory($service)
    {
        echo "</br>";
        $args = [];
        $servicePositions = [];
        if (is_array($service['arguments'])) {
            foreach ($service['arguments'] as $argument) {
                if (substr($argument, 0, 1) === '@') {
                    $serviceName = substr($argument, 1);
                    if (!array_key_exists($serviceName, $this->services)) {
                        throw new InvalidArgumentException("Error: service " . $serviceName . " not defined. Could not create service " . $service['name'] . ".");
                    }
                    $args[] = $this->services[$serviceName];
                    $servicePositions[] = sizeof($args) - 1;
                } else {
                    $args[] = $argument;
                }
            }
            foreach ($servicePositions as $index) {
                $args[$index] = $this->serviceFactory($args[$index]);
            }
        }
        $r = new ReflectionClass($service['class']);
        return $r->newInstanceArgs($args);
    }

}