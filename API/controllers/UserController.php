<?php

include_once './Database/database.php';
include_once './models/User.php';
class UserController
{
    public static function get()
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
            echo json_encode(array("code" => 404, "massage" => "No data found"));
        }
        return json_encode($users);
    }
}
