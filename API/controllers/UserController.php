<?php

include_once './Database/database.php';
include_once './models/User.php';
class UserController
{
    public static function users()
    {
        $database = new Database();
        $DB = $database->connect();
        $user = new User($DB);
        $users = $user->readUsers();
        if (!$users)
            return json_encode(array("code" => 404, "massage" => "No users were found."));
        return json_encode(array(
            "code" => 200,
            "datas" => $users,
        ));
    }
    public static function createUser($_,$datas){
        //Validation
        $errors=array();
        if(preg_match('/^[A-Za-z]*$/', $datas->first_name))
            $errors["first_name"]="First name must be can only contain letters";
        if(preg_match('/^[A-Za-z]*$/', $datas->last_name))
            $errors["last_name"]="Last name must be can only contain letters";
        if(!filter_var($datas->email_adress, FILTER_VALIDATE_EMAIL))
            $errors["email_adress"]="Email address must be valid";
        if(preg_match('/+[0-9]*$/', $datas->phone_number))
            $errors["phone_number"]="Phone number must be start with a '+' and end with a numbers";
        if(strlen($datas->phone_number) != 12)
            $errors["phone_number"]="Phone number must be 12 characters long";
        if(count($errors) > 0)
            return json_encode(array(
                "code"=> 400,
                "errors"=> $errors,
            ));
        //After validation
        $database = new Database();
        $DB = $database->connect();
        $user=new User($DB);
        $newUser = $user->createUser($datas);
        return json_encode(array(
            "code" => 201,
            "datas" => $newUser,
        ));
    }
}
