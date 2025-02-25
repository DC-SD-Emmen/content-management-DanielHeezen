<?php
class UserManager
{
    private $conn;

    public function __construct(UserDatabase $db)
    {
        $this->conn = $db->getConnection();
    }


    // insert form data into the database aka create account function
    public function insert($data)
    {
        // script injection is no more
        $username = htmlspecialchars($data['username']);
        $password = password_hash($data['password'], PASSWORD_DEFAULT);


        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";

        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            // if the username already exists don't insert
            if ($stmt->fetchColumn() > 0) {
                echo "Username already exists, try logging in.";
                return;
            }

            $stmt = $this->conn->prepare($sql);
            // bind parameter to have no sql injection
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            echo "It send it to database :O";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function login($data)
    {
        // script injection is no more
        $username = htmlspecialchars($data['username']);
        $password = $data['password'];


        $sql = "SELECT * FROM users WHERE username = :username";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // If there is no username with that name
            if (!$user) {
                echo "Username not found!";
                return;
            }

            if (password_verify($password, $user['password'])) {
                ///start a session///
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

            } else {
                echo "Password is incorrect";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

}