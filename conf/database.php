<?php
class database{
    private $host = "localhost";
    private $db_name = "women_fintech";
    private $user = "root";
    private $pass = "";
    public $conn;

    public function getConnection(){
        try{
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch (PDOException $e){
            echo "Connection Error: ".$e->getMessage();
        }
        return $this->conn;
    }

}



?>