<?php
class UserDatabase {
    private $servername = "mysql";
    private $username = "root";
    private $password = "root";
    private $dbname = "user_login";
    private $conn;


    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname;", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // Method to get the current connection
    public function getConnection() {
        return $this->conn;
    }


    public function __destruct() {
        $this->conn = null;
    }
}
?>
