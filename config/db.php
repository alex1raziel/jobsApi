<?php
class DataBase {
    private $host='localhost';
    private $username='api';
    private $pass='Aa123456';
    private $db_name='jobs';
    private $port='3308';
    private $conn;

    public function connect(){
        $this->conn = null;

        try{
            $this->conn = new PDO('mysql:host='.$this->host.';port='.$this->port.';dbname='.$this->db_name,$this->username,$this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            echo 'Connection Error: '.$e->getMessage();
        }
        return $this->conn;
    }
}
