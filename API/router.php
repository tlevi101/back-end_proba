<?php
class Router
{
    private $handlers = array();

    public function get($path, $handler)
    {
        $this->newHandler('GET', $path, $handler);
    }

    public function post($path, $handler)
    {
        $this->newHandler('POST', $path, $handler);
    }

    private function newHandler($method, $path, $handler)
    {
        $this->handlers[$method . "_" . $path] = array(
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        );
    }


    public function run()
    {
        $requestURI = parse_url($_SERVER['REQUEST_URI']);
        $path = $requestURI['path'];
        $method = $_SERVER['REQUEST_METHOD'];
        if (!array_key_exists($method . "_" . $path, $this->handlers)) {
            echo json_encode(array("code" => 404, "message" => "Page not Found"));
            return;
        }
        $handler = $this->handlers[$method . "_" . $path]["handler"];
        echo $handler();
    }
}
