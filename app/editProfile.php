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
    if (isset($_POST['go-back'])) {
        header("Location: index.php");
        exit();
    }

    $db = new Database();
    $userManager = new UserManager($db->getConnection());

    if (isset($_POST['updateProfile'])) {
        if (isset($_SESSION['user_id'])) {
            $userManager->updateProfile();
        }
        header("Location: index.php");
        exit();
    }

    if (isset($_POST['removeUser'])) {
        if (isset($_SESSION['user_id'])) {
            $userManager->removeUserGames();
            $userManager->removeUser();
        }
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }



    if (isset($_POST['changePassword'])) {
        $password = $_POST['password'];
        $username = $_POST['username'];
        $passwordCheck = $_POST['passwordCheck'];
//        --- Regex ---
        if (preg_match('/^.{5,}$/', $_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $userManager->updateProfile($username, $password, $passwordCheck);
        }else
            return "<div id = 'error'>For password, use atleast 5 characters.</div>";
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
    </div>


    <div id= "games">

        <div id= "addGameContainer">
            <div id="editGameText">
                <h2>Your profile</h2>
                <?php
                $username = $_SESSION['username'];
                echo "<h4>Name: $username</h4>"
                ?>
            </div>
            <div id="delete">
                <form method="POST">
                    <input class="delete" type="submit" name="removeUser" value="Delete Account" >
                </form>
            </div>
        </div>

        <div class= "library">
            <form method="POST" id="go-back">
                <button class="back-button" name="go-back">< Back</button>

                <div id="editProfile-box">
                    <form method="POST">
                        <h1>Edit Profile</h1>
                        <div><input class="username" name="username" placeholder="Username" required></div>
                        <div><input class="password" name="password" type="password" placeholder="Password" required></div>
                        <h2>Verify:</h2>
                        <div><input class="password" name="passwordCheck" type="password" placeholder="Password"></div>
                        <div><input class="submit" name="changePassword" type="submit" value="Change Profile" ></div>
                    </form>
                </div>
            </form>
        </div>


    </div>
</div>


</body>
</html>

