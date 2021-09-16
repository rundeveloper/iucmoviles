<?php
class Database{
    
    private $db_host = 'bjeinhljkvgjxtnufqej-mysql.services.clever-cloud.com';
    private $db_name = 'bjeinhljkvgjxtnufqej';
    private $db_username = 'uohtx3roycvhvief';
    private $db_password = 'SBCu4PUBWCZoEAwXkE9P';    
    
    public function dbConnection(){
        try{
            $conn = new PDO('mysql:host='.$this->db_host.';dbname='.$this->db_name,$this->db_username,$this->db_password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        }
        catch(PDOException $e){
            echo "Connection error ".$e->getMessage(); 
            exit;
        }
    }
}
?>