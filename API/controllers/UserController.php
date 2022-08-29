<?php

include_once './Database/database.php';
include_once './models/User.php';
class UserController
{
    public static function users()
    {
        $users = array();
        $database = new Database();
        $DB = $database->connect();
        $user = new User($DB);
        $result = $user->readUsers();
        if ($result->rowCount() !== 0) {

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user = array(
                    'id' => $row['id'],
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                    'email_address' => $row['email_address'],
                    'password' => $row['password'],
                    'phone_number' => $row['phone_number']
                );
                array_push($users, $user);
            }
        } else {
            return json_encode(array("code" => 404, "massage" => "No data found"));
        }
        return json_encode(array(
            "code" => 201,
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
        //After validation
        $database = new Database();
        $DB = $database->connect();
        $user=new User($DB);
        $newUser = $user->createUser($datas);
        var_dump($newUser);
        return json_encode(array(
            "code" => 201,
            "datas" => $newUser,
        ));
    }
}
