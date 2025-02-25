<?php
    session_start();
    $host = "mysql"; // Le host est le nom du service, présent dans le docker-compose.yml
    $dbname = "my-wonderful-website";
    $charset = "utf8";
    $port = "3306";
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drenthe College docker web server</title>
    <link rel="stylesheet" href="mainStylesheet.css">
</head>
<body>


<?php

    spl_autoload_register(function($class_name) {
        include "classes/" . $class_name . ".php";
    });

    //het maken van een nieuwe database instantie voert ook direct de __construct() functie uit
    $db = new Database();
    $gm = new GameManager($db);

    if ($_SERVER["REQUEST_METHOD"] == 'POST') {
        // Handle the file upload and store the file path
        $filePath = $gm->fileuload($_FILES['photo']);  
        // Handle the form data insertion, including the file path
        $gm->insert($_POST, $_FILES['photo']['name'] );  
    }

?>









<div id= "backgroundContainer">

    <div id= "header">
        <h1> Game Library </h1>
        <h3> Ziet er slecht uit toch? </h3>
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
        <div id="library">
        
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




