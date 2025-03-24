<?php
    $host = "mysql";
    $dbname = "my-wonderful-website";
    $charset = "utf8";
    $port = "3306";


spl_autoload_register(function ($class_name) {
    include __DIR__ . "/classes/" . $class_name . ".php";
});


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $database = new UserDatabase();
    $um = new UserManager($database->getConnection());

    if (isset($_POST['submit']) && $_POST['submit'] === 'Log In') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $um->Login(['username' => $username, 'password' => $password]);

    } elseif (isset($_POST['submit']) && $_POST['submit'] === 'Sign Up') {
        $username = $_POST['username'];
//        --- Regex ---
        if (preg_match('/^.{5,}$/', $_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $um->insert(['username' => $username, 'password' => $password]);
        }
    }
}

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

</body>
</html>
