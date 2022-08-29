<?php
   class User{
    private $conn;
    private $table="users";
    public $id;
    public $first_name;
    public $last_name;
    public $password;
    public $email_adress;
    public $phone_number;

    public function __construct($db){
        $this->conn = $db;
    }
    public function readUsers(){
        $query="SELECT * FROM ".$this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function createUser($datas){
        $options = [
            'cost' => 12,
        ];
        $hashed_password= password_hash($datas->password, PASSWORD_BCRYPT, $options);
        $query="INSERT INTO ".$this->table."
        (`first_name`, `last_name`, `password`, `email_address`, `phone_number`) 
        VALUES ('".
            $datas->first_name."','".
            $datas->last_name."','".
            $hashed_password."','".
            $datas->email_address."','".
            $datas->phone_number."')";
        try {
            $stmt = $this->conn->prepare($query);
            $this->conn->beginTransaction();
            $stmt->execute();
            $resultID=$this->conn->lastInsertId();
            $this->conn->commit();
        
            return array(
                "id" => $resultID,
                "first_name" => $datas->first_name,
                "last_name" => $datas->last_name,
                "email_address" => $datas->email_address,
                "phone_number" => $datas->phone_number,
            ); 
        }
        catch(PDOException $e){
            $this->conn->rollback();
            echo $query . "<br>" . $e->getMessage();
            return $e->getMessage();
        }
    }
   }
