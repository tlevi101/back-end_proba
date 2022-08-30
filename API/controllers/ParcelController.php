<?php
// include_once "./API/controllers/*";
include_once './Database/database.php';
include_once './models/Parcel.php';
class ParcelController{
    public static function listParcels($params,$_){
        $database = new Database();
        $DB = $database->connect();
        $parcel = new Parcel($DB);
        if(!array_key_exists('parcel_number',$params))
            return json_encode(array("code" => 400, "message" => "Parcel number param required"));
        $parcels = $parcel->findALL("parcel_number='".$params['parcel_number']."'");
        if(!$parcels){
            return json_encode(array("code" => 404, "message" => "Paracels not found"));
        }
        $parcels=array_map(function($parcel){
            $user = UserController::user($parcel['user_id']);
            unset($parcel['user_id']);
            $parcel['user']= json_decode($user);
            return $parcel;
        },$parcels);
        return json_encode($parcels);
    }
}