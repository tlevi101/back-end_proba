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
    public function readParcels(){
        $query="SELECT * FROM".$this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
   }
