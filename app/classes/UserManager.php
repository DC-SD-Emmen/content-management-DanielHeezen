<?php
class UserManager
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    // insert form data into the database aka create account function
    public function insert($data)
    {
        // script injection is no more
        $username = htmlspecialchars($data['username']);
        $password = $data['password'];


        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            // if the username already exists don't insert
            if ($stmt->fetchColumn() > 0) {
                return "Username already exists, try logging in.";
            }

            $stmt = $this->conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            // bind parameter to have no sql injection
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            return "It send it to database :O";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
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
                return "Username not found!";
            }

            if (password_verify($password, $user['password'])) {
                ///start a session///
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: http://localhost/index.php");
                exit();

            } else {
                return "Password is incorrect";
            }
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function insertMyGame($user_id, $game_id)
    {
        $checkSql = "SELECT COUNT(*) FROM user_games.user_games WHERE user_id = :user_id AND game_id = :game_id";  // Use the same database as getMyGames
        try {
            // Check if the game is already in the user's library
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->bindParam(':user_id', $user_id);
            $checkStmt->bindParam(':game_id', $game_id);
            $checkStmt->execute();

            $count = $checkStmt->fetchColumn();

            if ($count > 0) {
                return "Game is already in library.";
            } else {
                // insert game into user_games table using the same database
                $sql = "INSERT INTO user_games.user_games (user_id, game_id) VALUES (:user_id, :game_id)";  // Same database as getMyGames
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':game_id', $game_id);
                $stmt->execute();
            }
        } catch(PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }


    public function getMyGames() {
        $user_games = [];

        if (!isset($_SESSION['user_id'])) {
            return [];
        }

        try {
            // get game ids from the current user
            $sql = "SELECT user_games.game_id FROM user_games.user_games user_games WHERE user_games.user_id = :user_id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();
            $game_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (empty($game_ids)) {
                return [];
            }

            // creates an array with values of '?'
            $placeholders = implode(',', array_fill(0, count($game_ids), '?'));
            $sql = "SELECT * FROM gamelibrary.games WHERE id IN ($placeholders)";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($game_ids);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // create Game objects for each game
            foreach ($result as $row) {
                $game = new Game($row);
                $user_games[] = $game;
            }
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }

        return $user_games;
    }



    public function removeFromMyLibrary($game_id) {

        $user_id = $_SESSION['user_id'];

        // Use prepared statement to avoid SQL injection
        try {
            // Prepare the SQL query
            $stmt = $this->conn->prepare("DELETE FROM user_games.user_games WHERE game_id = :game_id AND user_id = :user_id");
            // Bind parameters
            $stmt->bindParam(':game_id', $game_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            // Execute the query
                return "Game successfully removed from your library.";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }


    public function removeFromLibrary($game_id) {
        $sql = "DELETE FROM games WHERE id = $game_id";

        // Execute the query
        if ($this->conn->query($sql)) {
        } else {
            return "Error deleting game: " . $this->conn->error;
        }

    }

    public function removeUserGames() {

        $user_id = $_SESSION['user_id'];

        // Use prepared statement to avoid SQL injection
        try {
            // Prepare the SQL query
            $stmt = $this->conn->prepare("DELETE FROM user_games.user_games WHERE user_id = :user_id");
            // Bind parameters
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            // Execute the query
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function removeUser() {

        $user_id = $_SESSION['user_id'];

        // Use prepared statement to avoid SQL injection
        try {
            $stmt = $this->conn->prepare("DELETE FROM users WHERE id = :user_id");
            // Bind parameters
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            // Execute the query
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }







    public function updateProfile($username, $password, $passwordCheck) {
        $user_id = $_SESSION['user_id']; // Get user ID from session

        try {
            // Fetch the current hashed password from the database
            $stmt = $this->conn->prepare("SELECT password FROM users WHERE id = :user_id");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $currentPassword = $stmt->fetchColumn(); // Get hashed password from DB

            // Verify if the entered password and the one in database are the same
            if (!password_verify($passwordCheck, $currentPassword)) {
                return "Error: Current password is incorrect.";
            }


            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE username = :username AND id != :user_id");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->fetchColumn() > 0) {
                return "Error: Username already taken.";
            }
            

            $stmt = $this->conn->prepare("UPDATE users SET password = :password, username = :username WHERE id = :user_id");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->execute();

            return "Success: Profile updated successfully.";
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }



}