<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
include_once "./API/Router.php";
include_once "./API/controllers/UserController.php";
$router = new Router();

$router->get("/users", [UserController::class, 'users']);
$router->post("/users", [UserController::class, 'createUser']);

$router->run();
