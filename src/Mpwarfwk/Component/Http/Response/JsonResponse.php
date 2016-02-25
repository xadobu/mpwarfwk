<?php

namespace Mpwarfwk\Component\Http\Response;

class JsonResponse extends Response
{
    public function send()
    {
        header('Content-Type: application/json');
        echo json_encode($this->content);
    }
}