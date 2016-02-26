<?php

namespace Mpwarfwk\Component\Http\Request;

class Request
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    protected $query;
    protected $request;
    protected $files;
    protected $server;
    protected $cookies;
    protected $session;
    protected $header;
    protected $content;

    protected $pathInfo;
    protected $params;

    protected function __construct(array $query = array(), array $request = array(), array $files = array(), array $cookies = array(), array $server = array(), array $session = array(), array $header = array(), $content = "")
    {
        $this->query = $query;
        $this->request = $request;
        $this->files = $files;
        $this->cookies = $cookies;
        $this->server = $server;
        $this->session = $session;
        $this->header = $header;
        $this->cookies = $content;
    }

    public static function createFromGlobals()
    {
        session_start();
        return new Request($_GET, $_POST, $_FILES, $_COOKIE, $_SERVER, $_SESSION, getallheaders(), file_get_contents('php://input'));
    }

    public function getPathInfo()
    {
        if (null === $this->pathInfo) {
            $this->pathInfo = $this->preparePathInfo();
        }

        return $this->pathInfo;
    }

    protected function preparePathInfo()
    {
        return parse_url($this->server['REQUEST_URI'])['path'];
    }

    public function getMethod()
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function getQuery($key = null, $default = null)
    {
        if (is_null($key)) return $this->query;
        if (array_key_exists($key, $this->query)) return $this->query[$key];
        return $default;
    }

    public function getRequest($key = null, $default = null)
    {
        if (is_null($key)) return $this->request;
        if (array_key_exists($key, $this->request)) return $this->request[$key];
        return $default;
    }

    public function getServer($key = null, $default = null)
    {
        if (is_null($key)) return $this->server;
        if (array_key_exists($key, $this->server)) return $this->server[$key];
        return $default;
    }

    public function getCookies($key = null, $default = null)
    {
        if (is_null($key)) return $this->cookies;
        if (array_key_exists($key, $this->cookies)) return $this->cookies[$key];
        return $default;
    }

    public function getSession($key = null, $default = null)
    {
        if (is_null($key)) return $this->session;
        if (array_key_exists($key, $this->session)) return $this->session[$key];
        return $default;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function get($key = null, $default = null)
    {
        if (is_null($key)) return $this->params;
        if (array_key_exists($key, $this->params)) return $this->params[$key];
        return $default;
    }

    public function setParams(array $params)
    {
        $this->params = $params;
    }


}