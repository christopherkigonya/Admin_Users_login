<?php
session_start();

//connecting database
$db = mysqli_connect("localhost", "root", "", "multi_login");

// variable declaration
$username = "";
$email = "";
$errors = [];

//call the register() funtion if register_btn is clicked

if (isset($_POST["register_btn"])) {
    register();
}
//register user this is the register function
function register()
{
    /* we call all the variables using the global keyword to make them available
    in the function */
    global $db, $errors, $username, $email;
    //recieve all input values from the form using the e() function
    // define below to escape form values
    $username = e($_POST["username"]);
    $email = e($_POST["email"]);
    $password_1 = e($_POST["password_1"]);
    $password_2 = e($_POST["password_2"]);

    //form validation to ensure that every field is filled
    if (empty($username)) {
        $errors[] = "username is required Boss!";
    }
    if (empty($email)) {
        $errors[]= "So you want to live email field empty?!";
    }
    if (empty($password_1)) {
        $errors[] = "Atleast add something in the password field";
    }
    if ($password_1 != $password_2) {
        $errors[] = "The passwords don't match Boss!";
    }
    // Now if there are no errors in the form, registration will take place
    if (count($errors) == 0) {
        $password = md5($password_1); // this will encrypt the password before saving it in the database
        if (isset($_POST["user_type"])) {
            $user_type = e($_POST["user_type"]);
            $query = "INSERT INTO users (username, email, user_type, password)
            VALUES('$username', '$email', '$user_type', '$password')";
            mysqli_query($db, $query);
            $_SESSION['SUCESS!'] = "NEW USER SUCESSFULLY REGISTERED";
            header('location:home.php');

        } else {
            $query = "INSERT INTO users (username, email, user_type, password)
            VALUES ('$username', '$email', 'user', '$password')";
            mysqli_query($db, $query);

            // get id of the created user
            $logged_in_user_id = mysqli_insert_id($db);
            $_SESSION["user"] = getUserById($logged_in_user_id);// put logged in user in session

            $_SESSION["success"] = "you are now logged in";
            header('location: index.php');
        }
    }
}
function getUserById($id)
{
    global $db;
    $query = "SELECT * FROM users WHERE id =" . $id;
    $result = mysqli_query($db, $query);

    $user = mysqli_fetch_assoc($result);
    return $user;
}
//escape string
function e($val)
{
    global $db;
    return mysqli_real_escape_string($db, trim($val));

}
function display_error()
{
    global $errors;

    if (count($errors) > 0) {
        echo "<div class = 'errors'>";
        foreach ($errors as $error) {
            echo $error . '<br>';
        }
        echo '</div>';
    }
}
//this function below helps to prevent unlogged in users from acessing specific pages.
function isLoggedin()
{
    if(isset($_SESSION['user'])){
        return true;
    }
    else {
        return false;
    }
}
//log user out function when logout button is clicked
function logout()
{
    if(isset($_GET['logout'])){
        session_destroy();
        unset($_SESSION['user']);
        header("location: login.php");
    }
}
function login(){
    global $db, $username, $errors; //this is to make access to variables outside the funcion
    // grap form vlaues
    $username = e($_POST['username']);
    $password = e($_POST['password']);

    if(empty($username)){
        $errors[] = 'INSERT USERNAME BOSS!';
    }
    if(empty($password)){
        $errors[] = 'Password is required BOSS!';
    }
    if(count($errors)==0){
        $password =md5($password);
        $query = 'SELECT * FROM users WHERE username="$username" AND password = "$password" LIMIT 1';
        $results = mysqli_query($db,$query);
        if(mysqli_num_rows($results) == 1){
            // now user is found let's check if is admin
            $logged_in_user = mysqli_fetch_assoc($results);
        if($logged_in_user['user_type']=='admin'){
                $_SESSION['user'] = $logged_in_user;
                $_SESSION['success'] = "you are now logged in";
                header("location: admin/home.php");
            }else{
                $_SESSION["user"] = $logged_in_user;
                $_SESSION["success"] = "you are now logged in";
                header("location: index.php");
            }
        }else{
                $errors[] = "wrong username OR password combination";
            }
        }
    }

?>