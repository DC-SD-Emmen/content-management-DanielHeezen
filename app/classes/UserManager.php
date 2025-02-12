<?php
class UserManager
{
    private $conn;

    public function __construct(Database $db)
    {
        $this->conn = $db->getConnection();
    }

    // Insert form data into the database
    public function insert($data)
    {
//        script injection is no more
        $username = htmlspecialchars($data['username']);
        $password = password_hash($data['password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, password) 
                VALUES (:username, :password)";

        try {
            $stmt = $this->conn->prepare($sql);
            //bind parameter to have n sql injection
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            echo "It send it to database :O";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

}
