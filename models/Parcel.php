<?php
class Parcel
{
    private $conn;
    private $table = "parcels";
    public $id;
    public $parcel_number;
    public $size;
    public $use_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function findAll($condition)
    {
        $parcels = array();
        $condition = $condition ?? true;
        $query = "SELECT * FROM " . $this->table . " where " . $condition;
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
    public function createParcel($datas)
    {

        $parcels = $this->findAll(true);
        $parcels = array_map(function ($parcel) {
            return $parcel["parcel_number"];
        }, $parcels);

        $parcel_number = $this->randString(10);
        while (count(array_intersect($parcels, array($parcel_number))) != 0) {
            $parcel_number = $this->randString(10);
        }
        $query = "INSERT INTO " . $this->table . "
        (`parcel_number`, `size`, `user_id`) 
        VALUES ('" .
            $parcel_number . "','" .
            $datas['size'] . "','" .
            $datas['user_id'] . "')";
        try {
            $stmt = $this->conn->prepare($query);
            $this->conn->beginTransaction();
            $stmt->execute();
            $resultID = $this->conn->lastInsertId();
            $this->conn->commit();

            return array(
                "id" => $resultID,
                "size" => $datas['size'],
                "parcel_number" => $parcel_number,
                "user_id" => $datas['user_id'],
            );
        } catch (PDOException $e) {
            $this->conn->rollback();
            echo $query . "<br>" . $e->getMessage();
            return $e->getMessage();
        }
    }
    private function randString($length)
    {
        $str = "abcdef0123456789";
        $str = str_shuffle($str);
        $rand = '';
        $l = strlen($str) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand .= $str[mt_rand(1, $l)];
        }
        return $rand;
    }
}
