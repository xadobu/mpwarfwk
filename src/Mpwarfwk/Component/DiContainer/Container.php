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
    private $serviceInstances = [];
    private $tags = [];

    public function __construct($path)
    {
        $this->parser = new YAMLParser();
        $this->addServicesFromYAML($path, true);
    }

    public function addServicesFromYAML($path, $strict = false)
    {
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
                    if (is_array($tag)) {
                        $key = array_keys($tag)[0];
                        $this->tags[$key][$tag[$key]] = $rawService['name'];
                    } else {
                        $this->tags[$tag][] = $rawService['name'];
                    }
                }
            }

            $services[$rawService['name']] = [
                'name' => $rawService['name'],
                'class' => $rawService['class'],
                'arguments' => $rawService['arguments'],
                'public' => $rawService['public'],
                'singleton' => $rawService['singleton'],
                'resolved' => false
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

        if (!$service['public']) {
            throw new InvalidArgumentException("Error: service " . $serviceName . " is not public.");
        }
        return $this->getService($service);
    }

    private function getService($service)
    {
        if (!$service['resolved']) {
            $this->resolve($service);
            $this->services[$service['name']]['resolved'] = true;
        }
        if ($service['singleton']) {
            return $this->serviceInstance($this->services[$service['name']]);
        }
        return $this->serviceFactory($this->services[$service['name']]);
    }

    private function serviceInstance($service)
    {
        if (is_bool($service['singleton'])) {
            if (!array_key_exists($service['name'], $this->serviceInstances)) {
                $this->serviceInstances[$service['name']] = $this->serviceFactory($service);
            }
            return $this->serviceInstances[$service['name']];
        }
        $args = $this->getServiceArguments($service);
        return forward_static_call_array(array($service['name'], $service['singleton']), $args);
    }

    private function serviceFactory($service)
    {
        $args = $this->getServiceArguments($service);
        $r = new ReflectionClass($service['class']);
        return $r->newInstanceArgs($args);
    }

    private function resolve($service)
    {
        if (is_null($service['arguments'])) {
            return;
        }
        $args = [];
        $services = [];
        foreach ($service['arguments'] as $argument) {
            if (is_array($argument)) {
                $args[] = $argument;
            }
            if (substr($argument, 0, 1) === '@') {
                $serviceName = substr($argument, 1);
                if (!array_key_exists($serviceName, $this->services)) {
                    throw new InvalidArgumentException("Error: service " . $serviceName . " not defined. Could not create service " . $service['name'] . ".");
                }
                $args[] = $this->services[$serviceName];
                $services[] = sizeof($args) - 1;
            } else {
                $args[] = $argument;
            }
        }
        foreach ($services as $index) {
            $args[$index] = $this->getService($args[$index]);
        }
        $service['arguments'] = $args;
        $this->services[$service['name']] = $service;
    }

    private function getServiceArguments($service)
    {
        if (is_null($service['arguments'])) {
            $service['arguments'] = [];
        }
        return $service['arguments'];
    }
}