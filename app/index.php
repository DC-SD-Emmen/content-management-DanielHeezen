<?php

    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Handle logout functionality
        if (isset($_POST['log_out'])) {
            session_unset();
            session_destroy();
            header("Location: login.php");
            exit();
        }
        if (isset($_POST['myLibrary'])) {
            header("Location: myLibrary.php");
            exit();
        }
    }

    if (!isset($_SESSION['username']) || $_SESSION['username'] == "") {
        header('Location: login.php');
        exit();
    }

    spl_autoload_register(function($class_name) {
        include "classes/" . $class_name . ".php";
    });

    //het maken van een nieuwe database instantie voert ook direct de __construct() functie uit
    $db = new Database();
    $gm = new GameManager($db->getConnection());
    $userManager = new UserManager($db->getConnection());

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Handle file upload and form data insertion
        if (isset($_FILES['photo'])) {
            // Handle the file upload and store the file path
            $filePath = $gm->fileuload($_FILES['photo']);
            // Handle the form data insertion, including the file path
            $gm->insert($_POST, $_FILES['photo']['name']);
        }
    }

?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drenthe College docker web server</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="java.js"></script>
    <link rel="stylesheet" href="mainStylesheet.css">
</head>
<body>


<div id= "backgroundContainer">

    <div id= "header">
        <div id="toggle">
            <form method="POST">
                <button id="myLibrary" type="submit" name="myLibrary">My Library</button>
                <button id="logOut" type="submit" name="log_out">Log Out</button>
            </form>
        </div>
        <h1> Game Library </h1>
    </div>

   
    <div id= "games">


    <!-- --------------------LEFT BOX-------------------- -->
        <div id= "addGameContainer">
            <div id="addGameText">
                <h2>Add Game</h2>
            </div>


            <form method="POST" action="" enctype="multipart/form-data">

                <div id= "game-form">

                    <label for='title'>title:</label>
                    <input type="text" name="title" placeholder="title" required>

                    <label for='genre'>genre:</label>
                    <input type="text" name="genre" placeholder="genre" required>

                    <label for='platform'>platform:</label>
                    <input type="text" name="platform" placeholder="platform" required>

                    <label for='release_year'>release Year:</label>
                    <input type="date" name="release_year" placeholder="release_year" required>

                    <label for='rating'>rating:</label>
                    <input type="number" name="rating" placeholder="rating" required>

                    <label for='photo'>photo:</label>
                    <input type="file" name="photo" required>
                    <br>

                    <input type="submit" name="submit" value="Submit">

                </div>
            </form>
        </div>
    <!-- ------------------------------------------------ -->

    <!-- --------------------RIGHT BOX-------------------- -->
        <div class="library">
        
            <?php

                $games = $gm->getAllGames();

                foreach($games as $game) {
                    echo "<a href='game_details.php?id=" . $game->get_id() . "'>
                    <div id='game-boxes'>
                    <img class='game-image' src='uploads/" . $game->get_photo() . "'>
                    <h1>" . $game->get_title() . "</h1>
                    </div>
                    </a>";
                
                }

            ?>
        
        </div>

     <!-- ------------------------------------------------ -->
      
    </div>
</div>

</body>
</html>
