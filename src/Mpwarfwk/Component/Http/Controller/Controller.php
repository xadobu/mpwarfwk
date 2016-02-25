<?php

namespace Mpwarfwk\Component\Http\Controller;

use Mpwarfwk\Component\DiContainer\Container;

abstract class Controller
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}