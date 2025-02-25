<?php
    session_start();
    $host = "mysql";
    $dbname = "my-wonderful-website";
    $charset = "utf8";
    $port = "3306";
?>

<html>
    <head>
        <title>Drenthe College docker web server</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="java.js"></script>
        <link rel="stylesheet" href="stylesheet.css">
    </head>

<body>
<iframe height="80px" width="300px" frameborder="0" src=https://livecounts.io/embed/youtube-live-subscriber-counter/UCojBmluqa5vb6oTHKqIMh6g style="border: 0; width:300px; height:80px;"></iframe>
<iframe height="80px" width="300px" frameborder="0" src=https://livecounts.io/embed/youtube-live-subscriber-counter/UCW8Y4FvpRw0qEamIzdYSakA style="border: 0; width:300px; height:80px;"></iframe>
    <?php
    spl_autoload_register(function($class_name) {
        include __DIR__ . "/classes/" . $class_name . ".php";
    });

    ?>

    <div id="toggle">
        <button id="logIn" style="display: none">Log In</button>
        <button id="signUp" >Register</button>
    </div>

    <div id="form-Container">

        <!-- Login Form -->
        <form method="POST" id="login-form">
            <h1>Log In</h1>
            <div><input class="username" name="username" placeholder="Username" required></div>
            <div><input class="password" name="password" type="password" placeholder="Password" required></div>
            <div><input class="submit" name="submit" type="submit" value="Log In" ></div>
        </form>

        <!-- Sign Up Form -->
        <form method="POST" id="signup-form" style="display: none;">
            <h1>Register</h1>
            <div><input class="username" name="username" placeholder="Username" required></div>
            <div><input class="password" name="password" type="password" placeholder="Password" required></div>
            <div><input class="submit" name="submit" type="submit" value="Sign Up" ></div>
        </form>
    </div>

    <div id="errorMessage">
        <?php

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Get POST data
                $username = $_POST['username'];
                $password = $_POST['password'];

                $database = new UserDatabase();
                $userManager = new UserManager($database);

                if (isset($_POST['submit']) && $_POST['submit'] === 'Log In') {
                    $userManager->Login(['username' => $username, 'password' => $password]);
                }
                elseif (isset($_POST['submit']) && $_POST['submit'] === 'Sign Up') {
                    $userManager->insert(['username' => $username, 'password' => $password]);
                }
            }
        ?>
    </div>

</body>
</html>
