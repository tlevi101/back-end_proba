<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
include_once "./API/Router.php";
include_once "./API/controllers/UserController.php";
include_once "./API/controllers/ParcelController.php";
$router = new Router();

$router->get("/users", [UserController::class, 'users']);
$router->post("/users", [UserController::class, 'createUser']);
$router->get("/parcels/{parcel_number}", [ParcelController::class, 'listParcels']);
$router->post("/parcels", [ParcelController::class, 'createParcel']);

$router->run();
