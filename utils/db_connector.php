<?php

class DBConnector {
    private array $config;
    
    private mysqli $conn;
    
    private string $host;
    private string $user;
    private string $password;
    private string $dbname;
    private int $port;

    public function __construct(array $config) {
        $this->config = $config;
        
        $this->dbname = $config['db_name'];
        $this->user = $config['db_user'];
        $this->password = $config['db_password'];
        $this->host = $config['db_host'];
        $this->port = $config['db_port'];
    }

    public function get_conn(): mysqli{
        if(isset($this->conn)) {
            return $this->conn;
        }else{
            $this->conn = $this->create_connection();
            return $this->conn;
        }
    }

    
    function create_database(): void{    
        $conn = new mysqli($this->host, $this->user, $this->password, null, $this->port);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $conn->query("CREATE DATABASE IF NOT EXISTS $this->dbname");
        if($conn->error){
            die("Error creating database: " . $conn->error);
        }
    }

    
    private function delete_database(): void{
        if(!$this->conn){
            $this->conn = $this->get_conn();
        }
        $query = "drop database $this->dbname;";
        if($this->conn->query($query)){
            echo "[*] Database deleted successfully!<br>";
        }else{
            if(strpos($this->conn->error, "Unknown database") >= 0){
                echo "[*] Database doesn't exist!<br>";
            }else{
                die("[!] Error deleting database: " . $this->conn->error);
            }
        }
    }

    
    function create_connection(): mysqli {
        try{
            $conn = new mysqli($this->host, $this->user, $this->password, $this->dbname, $this->port);
        }catch(Exception $e){
        // if ($conn->connect_error) {
            if(strpos($conn->connect_error, "Unknown database") >= 0){
                $this->create_database();
                return $this->create_connection();
            }
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

    
    public function close(): void{
        $this->conn->close();
    }

    
    public static function get_connection($config): mysqli {
        $db_connector = new DBConnector($config);
        $conn = $db_connector->get_conn();
        return $conn;
    }
}
?>
