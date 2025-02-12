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
        include __DIR__ . "/classes/" . $class_name . ".php";
    });

    ?>

    <div id="form-Container">

        <form method="POST">
            <div><input class="username" name="username" placeholder="Username" required></div>
            <div><input class="password" name="password" type="password" placeholder="Password" required></div>
            <div><input class="submit" name="submit" type="submit" value="Sumbit" ></div>
        </form>

    </div>

<?php
//    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//        // Get POST data
//        $username = $_POST['username'];
//        $password = $_POST['password'];
//
//        // Create instances of the required classes
//        $database = new Database();
//        $userManager = new UserManager($database);
//
//        // Call the insert method
//        $userManager->insert(['username' => $username, 'password' => $password]);
//    }
//    ?>

</body>
</html>
