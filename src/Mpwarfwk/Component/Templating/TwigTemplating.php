<?php

namespace Mpwarfwk\Component\Templating;

use Twig_Loader_Filesystem;
use Twig_Environment;

class TwigTemplating implements Templating
{
    private $params = [];
    private $twig;

    public function setTemplatesPath($path)
    {
        $loader = new Twig_Loader_Filesystem($path);
        $this->twig = new Twig_Environment($loader);
    }

    public function assign($param, $value = null)
    {
        if (is_array($param)) $this->params = $param;
        else $this->params[$param] = $value;
    }

    public function show($template)
    {
        echo $this->twig->render($template.".twig", $this->params);
    }
}