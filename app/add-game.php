<?php


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
       <link rel="stylesheet" href="addGameStylesheet.css">
</head>
<body>

<?php

spl_autoload_register(function($class_name) {
    include "classes/" . $class_name . ".php";
});

?>

<div id="top-container">

    <form method="POST">

    <div id= "go-back">
        <h2><a href="http://localhost/game-index.php"> < Go back </a> </h2>
        </div>

        <div id= "game-form">
            <label class= "title-text" for='game'>Title:
            <input type="text" name="title" placeholder="title" required>
            </label> <br><br>

            <label class= "genre-text" for='game'>Genre:
            <input type="text" name="genre" placeholder="genre" required> 
            </label> <br><br>

            <label class= "platform-text" for='game'>Platform:
            <input type="text" name="platform" placeholder="platform" required> 
            </label> <br><br>

            <label class= "release_year-text" for='game'>Release date:
            <input type="date" name="release_year" placeholder="year" required> 
            </label><br><br>

            <label class= "rating-text" for='game'>Rating:
            <input type="text" name="rating" placeholder="1-10" required> 
            </label> <br><br>

            <input class="submit" type="submit" name="submit" value="Sumbit" >

        </div>


    </div>

</body>
</html>

