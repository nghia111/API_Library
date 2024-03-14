<?php
class Database
{
    private $host = "localhost";
    private $username = "root";
    private $password = "Anhdasai123@";
    private $dbName = "lib";
    private $conn;
    public function __construct()
    {
    }
    public function connect()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbName . "", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "connection failed " . $e->getMessage();
        }
        return $this->conn;
    }
}
