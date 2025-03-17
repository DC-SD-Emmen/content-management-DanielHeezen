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
        if (isset($_POST['mainLibrary'])) {
            header("Location: index.php");
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
                <button id="myLibrary" type="submit" name="mainLibrary">Main Library</button>
                <button id="logOut" type="submit" name="log_out">Log Out</button>
            </form>
        </div>
        <h1> Your Game Library </h1>
    </div>


    <div id= "games">


        <!-- --------------------LEFT BOX-------------------- -->
        <div id= "addGameContainer">
            <div id="addGameText">
                <h2>This is your library</h2>
                <h4>this is where you can keep your own wishlisted items</h4>
            </div>
        </div>
        <!-- ------------------------------------------------ -->

        <!-- --------------------RIGHT BOX-------------------- -->
        <div class="library">

            <?php

            $games = $userManager->getMyGames();

            foreach ($games as $game) {
                echo "<a href='myGame_details.php?id=" . $game->get_id() . "'>
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
