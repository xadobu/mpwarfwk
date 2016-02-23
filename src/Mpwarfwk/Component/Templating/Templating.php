<?php

namespace Mpwarfwk\Component\Templating;


interface Templating
{
    public function setTemplatesPath($path);
    public function assign($param, $value = null);
    public function show($template);
}