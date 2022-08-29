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
   }
?>