<?php
class Database
{
    private $servername = "127.0.0.1";
    private $username = "root";
    private $password = "2569";
    private $conn;
    public function connect()
    {
        // echo "Connecting to " . $this->servername."<br>";
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=".$this->servername.";port=3307;dbname=csomagfelado;",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Connected successfully"."<br>";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage()."<br>";
        }
        return $this->conn;
    }
}

$db=new Database();
$db->connect();
