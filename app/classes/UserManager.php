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


        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";

        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            // if the username already exists don't insert
            if ($stmt->fetchColumn() > 0) {
                return "Username already exists, try logging in.";
            }

            $stmt = $this->conn->prepare($sql);
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
            echo "hi";

            $count = $checkStmt->fetchColumn();

            if ($count > 0) {
                echo "Game is already in library.";
            } else {
                // insert game into user_games table using the same database
                $sql = "INSERT INTO user_games.user_games (user_id, game_id) VALUES (:user_id, :game_id)";  // Same database as getMyGames
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':game_id', $game_id);
                $stmt->execute();
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }


    public function getMyGames() {
        $user_games = [];

        if (!isset($_SESSION['user_id'])) {
            return [];
        }

        try {
            // Fetch only the game IDs associated with the current user
            $sql = "SELECT ug.game_id FROM user_games.user_games ug WHERE ug.user_id = :user_id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();
            $game_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (empty($game_ids)) {
                return [];
            }

            // Now fetch game details for the game IDs retrieved
            $placeholders = implode(',', array_fill(0, count($game_ids), '?'));
            $sql = "SELECT * FROM gamelibrary.games WHERE id IN ($placeholders)"; // Query to get details for those game IDs

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($game_ids);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Create Game objects for each game
            foreach ($result as $row) {
                $game = new Game($row);
                $user_games[] = $game;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        return $user_games;
    }
}