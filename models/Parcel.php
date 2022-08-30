<?php
   class Parcel{
    private $conn;
    private $table="parcels";
    public $id;
    public $parcel_number;
    public $size;
    public $use_id;

    public function __construct($db){
        $this->conn = $db;
    }
    public function findALL($condition){
        $parcels=array();
        $condition = $condition ?? true;
        $query="SELECT * FROM ".$this->table." where ".$condition;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        if ($stmt->rowCount() !== 0) {

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $parcel = array(
                    'id' => $row['id'],
                    'parcel_number' => $row['parcel_number'],
                    'size' => $row['size'],
                    'user_id' => $row['user_id'],
                );
                array_push($parcels, $parcel);
            }
        } else {
            return null;
        }
        return $parcels;
    }
   }
