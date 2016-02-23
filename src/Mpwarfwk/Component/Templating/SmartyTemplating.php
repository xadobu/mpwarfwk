<?php

namespace Mpwarfwk\Component\Templating;

use Smarty;

class SmartyTemplating implements Templating
{
    private $params = [];
    private $path;

    public function setTemplatesPath($path)
    {
        $this->path = $path;
    }

    public function assign($param, $value = null)
    {
        if(is_array($param)) $this->params = $param;
        else $this->params[$param] = $value;
    }

    public function show($template)
    {
        $smarty = new Smarty;
        $smarty->caching = true;
        if( !$smarty->templateExists($template.".tpl") ){
            throw new TemplateNotFoundException("Template ". $template . " not found.");
        }
        if(!$smarty->isCached($template)) {

            foreach ($this->params as $key => $value) {
                $smarty->assign($key,$value);
            }
        }
        return $smarty->display($this->path."/".$template.".tpl");
    }
}