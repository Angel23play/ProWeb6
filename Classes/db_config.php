<?php

class Conexion{

private $server = 'localhost';
private $user = 'root';
private $password = '';
private $db_name = 'Db_NarutoShippuden';

private $conn; 

public function __construct(){

    $this->conn = new mysqli( $this->server ,$this->user,$this->password,$this->db_name );


    if($this->conn->connect_error){
        die('Conexion Fallida'. $this->conn->connect_error);
    }

} 


public function GetConexion() {
    return $this->conn;
}


}