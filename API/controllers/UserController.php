<?php

include_once './Database/database.php';
include_once './models/User.php';
class UserController
{
    public static function user($id){
        $database = new Database();
        $DB = $database->connect();
        $user = new User($DB);
        $result = $user->findOne($id);
        unset($result['password']);
        return json_encode($result);
    }
    public static function users()
    {
        $database = new Database();
        $DB = $database->connect();
        $user = new User($DB);
        $users = $user->findAll();
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
        if(!array_key_exists('first_name',$datas)){
            $errors["first_name"]="Firstname is required";
        }
        else if(preg_match('/^[A-Za-z]*$/', $datas['first_name']))
            $errors["first_name"]="Firstname can only contain letters";
        if(!array_key_exists('last_name',$datas)){
            $errors["last_name"]="Lastname is required";
        }
        else if(preg_match('/^[A-Za-z]*$/', $datas['last_name']))
            $errors["last_name"]="Last name can only contain letters";
        if(!array_key_exists('email_address',$datas)){
            $errors["email_address"]="Email is required";
        }
        else if(!filter_var($datas['email_address'], FILTER_VALIDATE_EMAIL))
            $errors["email_address"]="Email address must be valid";
        if(array_key_exists('phone_number',$datas)){
            if(preg_match('/"+"^[0-9]*$/', $datas['phone_number']))
                $errors["phone_number"]="Phone number must be start with a '+' and end with a numbers";
            if(strlen($datas['phone_number']) != 12)
                $errors["phone_number"]="Phone number must be 12 characters long";
        }
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
