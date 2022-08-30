<?php
class Router
{
    private $handlers = array();

    public function get($path, $handler)
    {
        $routePoints = explode('/', $path);
        $params = array();
        foreach ($routePoints as $index => $routePoint) {
            if (preg_match("/{.*}/", $routePoint)) {
                $routePoint = str_replace(['{', '}'], '', $routePoint);
                $params[$routePoint] = $index;
            }
        }
        $this->newHandler('GET', $path, $params, $handler);
    }

    public function post($path, $handler)
    {
        $routePoints = explode('/', $path);
        $params = array();
        foreach ($routePoints as $index => $routePoint) {
            if (preg_match("/{.*}/", $routePoint)) {
                $paramName = str_replace(['{', '}'], '', $routePoint);
                $params[$paramName] = $index;
            }
        }
        $this->newHandler('POST', $path, $params, $handler);
    }

    private function newHandler($method, $path, $params,  $handler)
    {
        array_push($this->handlers, array(
            'method' => $method,
            'path' => $path,
            'params' => $params,
            'handler' => $handler,
        ));
    }


    public function run()
    {
        $requestURI = parse_url($_SERVER['REQUEST_URI']);
        $path = $requestURI['path'];
        $routePoints = explode('/', $path);
        $params = array();
        $method = $_SERVER['REQUEST_METHOD'];
        $key_of_handler = 0;
        $page_found = false;
        $method_found_with_page = false;
        while ($key_of_handler < count($this->handlers) && !$method_found_with_page) {
            $handler = $this->handlers[$key_of_handler];
            $excepted_routePoints = explode('/', $handler['path']);
            $samePath = count($excepted_routePoints) == count($routePoints);
            if ($samePath) {
                foreach ($excepted_routePoints as $key => $excepted_routePoint) {
                    if (!str_contains($excepted_routePoint, '{')) {
                        $samePath = !$samePath && strcmp($excepted_routePoint, $routePoints[$key]) == 0;
                    }
                }
            }
            $page_found = $page_found || $samePath;
            $method_found_with_page = strcmp($handler['method'], $method) == 0 && $samePath;
            if (!$method_found_with_page)
                $key_of_handler++;
        }
        if (!$page_found) {
            echo json_encode(array("code" => 404, "message" => "Page not Found"));
            return;
        }
        if (!$method_found_with_page) {
            echo json_encode(array("code" => 501, "message" => "Bad method"));
            return;
        }
        $handler = $this->handlers[$key_of_handler]['handler'];
        foreach ($this->handlers[$key_of_handler]['params'] as $key => $paramIndex) {
            if (!array_key_exists($paramIndex, $routePoints) || $routePoints[$paramIndex] == "") {
                echo json_encode(array("code" => 401, "message" => "Missing parameter"));
                return;
            }
            $params[$key] = $routePoints[$paramIndex];
        }
        $postBody = json_decode(file_get_contents("php://input"), true);
        print_r($handler($params, $postBody));
    }
}
