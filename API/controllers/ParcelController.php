<?php
// include_once "./API/controllers/*";
include_once './Database/database.php';
include_once './models/Parcel.php';
class ParcelController
{
    public static function listParcels($params, $_)
    {
        $database = new Database();
        $DB = $database->connect();
        $parcel = new Parcel($DB);
        if (!array_key_exists('parcel_number', $params))
            return json_encode(array("code" => 400, "message" => "Parcel number param required"));
        $parcels = $parcel->findAll("parcel_number='" . $params['parcel_number'] . "'");
        if (!$parcels) {
            return json_encode(array("code" => 404, "message" => "Paracels not found"));
        }
        $parcels = array_map(function ($parcel) {
            $user = UserController::user($parcel['user_id']);
            unset($parcel['user_id']);
            $parcel['user'] = json_decode($user);
            return $parcel;
        }, $parcels);
        return json_encode($parcels);
    }
    public static function createParcel($_, $postBody)
    {
        $database = new Database();
        $DB = $database->connect();
        $_user = new User($DB);
        $errors = array();
        if (!array_key_exists('size', $postBody)) {
            $errors["size"] = "Size is required";
        } else if (count(array_intersect($postBody, ['S', 'L', 'M', 'XL'])) == 0)
            $errors["size"] = "Size can only be ['S','L', 'M', 'XL']";
        if (!array_key_exists('user_id', $postBody)) {
            $errors["user_id"] = "User_id is required";
        } else if (!is_int($postBody['user_id'])) {
            $errors["user_id"] = "User_id must be a number";
        } else if ($_user->findOne($postBody['user_id']) == null)
            $errors["user_id"] = "user not found";
        if (count($errors) > 0)
            return json_encode(array(
                "code" => 400,
                "errors" => $errors,
            ));

        $parcel = new Parcel($DB);
        $newParcel = $parcel->createParcel($postBody);
        $user = UserController::user($newParcel['user_id']);
        unset($newParcel['user_id']);
        $newParcel['user'] = json_decode($user);
        return json_encode($newParcel);
    }
}
