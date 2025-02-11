<?php

$host = "mysql";
$dbname = "my-wonderful-website";
$charset = "utf8";
$port = "3306";
?>

<html>
<head>
    <title>Drenthe College docker web server</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>

    <?php

    spl_autoload_register(function($class_name) {
        include "classes/" . $class_name . ".php";
    });

    ?>

    <div id="form-Container">

        <form method="POST">
            <div><input class="username" placeholder="Username" required></div>
            <div><input class="password" type="password" placeholder="Password" required></div>
            <div><input class="submit" type="submit" name="submit" value="Sumbit" ></div>
        </form>

    </div>
</body>
</html>
