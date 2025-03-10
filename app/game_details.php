<?php

session_start();


spl_autoload_register(function ($class_name) {
    include "classes/" . $class_name . ".php";
});


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
// Handle logout functionality
    if (isset($_POST['log_out'])) {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }

    $db = new Database();
    $userManager = new UserManager($db->getConnection());

    if (isset($_POST['add'])) {
        echo("hallo");
        if (isset($_GET['id'])) {
            // get the game ID as a string
            $game_id = $_GET['id'];
            // check if its an integer
            $game_id = filter_var($game_id, FILTER_SANITIZE_NUMBER_INT);
            if (isset($_SESSION['user_id'])) {
                // Get the user ID from the session
                $user_id = (string)$_SESSION['user_id'];

                $userManager->insertMyGame($user_id, $game_id);
            }
        }
    }
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drenthe College docker web server</title>
    <link rel="stylesheet" href="mainStylesheet.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="java.js"></script>
</head>
<body>





<div id= "backgroundContainer">

    <div id= "header">
        <div id="toggle">
            <form method="POST">
                <button id="logOut" type="submit" name="log_out">Log Out</button>
            </form>
        </div>
        <h1> Game Library </h1>
        <h3> Ziet er slecht uit toch? </h3>
    </div>

   
    <div id= "games">

        <div id= "addGameContainer">
            <div id="editGameText">
                <h2>Edit Game</h2>
            </div>
            <input class="delete" type="submit" name="submit" value="Delete game" >
            
        </div>


        <!-- --------------------CONTAINER FOR THE DETAILS OF ONE GAME-------------------- -->
        <div id= "gameDetails">

            <div id="go-back">
                <div class="back-button">
                    <h2> < Back </h2>
                </div>
            </div>


            <?php
                $servername = "mysql";
                $username = "root";
                $password = "root"; 
                $dbname = "gamelibrary"; 

                // Connect
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Haal de game ID op uit de URL parameter
                if (isset($_GET['id'])) {
                    $game_id = $_GET['id'];

                    // SQL injectie voorkomen
                    $game_id = mysqli_real_escape_string($conn, $game_id);

                    $sql = "SELECT * FROM games WHERE id = '$game_id'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Haal de gegevens van de game op
                        $row = $result->fetch_assoc();

                        // Instantiate the Game object
                        $game = new Game($row);  // Create a Game object using the fetched data

                        echo "<div id='game-pic'>
                            <img src='uploads/" . $game->get_photo() . "' alt='" . $game->get_title() . "'>
                        </div>";

                        echo "<div id='game-info'>
                            <h1> Title: " . $game->get_title() . "</h1>
                            <h1> Genre: " . $game->get_genre() . "</h1>
                            <h1> Platform: " . $game->get_platform() . "</h1>
                            <h1> Release year: " . $game->get_release_year() . "</h1>
                            <h1> Rating: " . $game->get_rating() . "/10 </h1>
                        </div>";
                    }
                } else {
                    echo "No game found";
                }

                $conn->close();
                ?>
            <div id="wishlist">
                <form method="POST">
                    <button id="add" type="submit" name="add">Add To Wishlist</button>
                </form>
            </div>
        </div>
    </div>
</div>


</body>
</html>

