<?php include("functions.php");

if (isset($_POST['login_btn'])){
    login();
    }
    ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Registration system PHP and MYSQL </title>
        <link rel = "stylesheet" type = "text/css" href = "style.css">
            </head>
            <body>
<div class = "hearder">
                    <h2>L0gin</h2>
</div>
<form method = "post" action = "login.php">
    <?php echo display_error(); ?>
    <div class = "input-group">
        <label>Username</label>
        <input type= "text" name = "username">
        <label>password</label>
        <input type = "password" name = "password">
    </div>
    <div class = "input-group">
        <button type = "submit" class = "btn" name = "login_btn">L0gin</button>

    </div>
    <p>
        Not yet a member? <a href= "register.php">Sign up</a>
        </p>
            </body>
</html>