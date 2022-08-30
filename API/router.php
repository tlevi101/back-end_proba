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
        array_push($this->handlers,array(
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ));
    }


    public function run()
    {
        $requestURI = parse_url($_SERVER['REQUEST_URI']);
        $path = $requestURI['path'];
        $method = $_SERVER['REQUEST_METHOD'];
        $key_of_handler=0;
        $page_found = false;
        $method_found_with_page = false;
        while ($key_of_handler<count($this->handlers) && !$method_found_with_page){
            $handler = $this->handlers[$key_of_handler];
            $page_found = $page_found || strcmp($handler['path'], $path)==0;
            $method_found_with_page = strcmp($handler['method'], $method)==0 && strcmp($handler['path'], $path)==0;
            if(!$method_found_with_page)
                $key_of_handler++;
        }
        if (!$page_found) {
            echo json_encode(array("code" => 404, "message" => "Page not Found"));
            return;
        }
        if(!$method_found_with_page) {
            echo json_encode(array("code" => 424, "message" => "Bad method"));
            return;
        }
        $postBody = json_decode(file_get_contents("php://input"));
        $handler = $this->handlers[$key_of_handler]['handler'];
        print_r($handler($_GET,$postBody));
    }
}
