<?php

namespace Mpwarfwk\Component\Http\Response;

class Response
{
    const HTTP_OK = 200;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_NOT_FOUND = 404;
    const HTTP_INTERNAL_SERVER_ERROR = 500;
    const HTTP_NOT_IMPLEMENTED = 501;

    protected $header;
    protected $content;
    protected $statusCode;

    public function __construct($header = array(), $content = "", $statusCode = self::HTTP_OK)
    {
        $this->header = $header;
        $this->content = $content;
        $this->statusCode = $statusCode;
    }

    public function send()
    {
        header(http_response_code($this->statusCode));
        if (is_array($this->header)) {
            foreach ($this->header as $key => $value) {
                header("{$key}:{$value}");
            }
        }
        echo $this->content;
    }
}