<?php
session_start();
require_once '../models/Database.php';
require_once '../models/User.php';

if (isset($_POST['login_submit'])) {
    
    $username = trim($_POST['username']);
    $password = $_POST['password'];


    //Serverside validation
    if (empty($username)){
        $_SESSION['error'] = "Username is required.";
        header("Location: ../views/auth/login.php");
        exit();
    }

    if (empty($password)){
        $_SESSION['error'] = "Password is required.";
        header("Location: ../views/auth/login.php");
        exit();
    }

    $user = new User();   

    $loggedInUser = $user->login($username, $password);

    if ($loggedInUser) {
        // Securely store user data in session
        $_SESSION['user_id'] = $loggedInUser['id'];
        $_SESSION['name'] = $loggedInUser['first_name'] . ' ' . $loggedInUser['last_name'];
        $_SESSION['username'] = $loggedInUser['username'];
        $_SESSION['user_role'] = $loggedInUser['role'];

        switch($loggedInUser['role']){
            case "Administrator": header("Location: ../views/dashboard/dashboard-admin.php"); break;
            case "Agent": header("Location: ../views/dashboard/dashboard-agent.php"); break;
        }      
        
        exit();
    } else {
        header("Location: ../views/auth/login.php");
        exit();
    }
}


//Signup
if (isset($_POST['signup_submit'])) {
    $first_name = trim($_POST['first-name']);
    $last_name = trim($_POST['last-name']);
    $username = trim($_POST['username']);
    $role = $_POST['role'];
    $phone_number = trim($_POST['phone_number']);
    $email = trim($_POST['email']);
    $password = $username; // Set password to username for initial login    

    //Serverside validation
    if (empty($first_name)){
        $_SESSION['error'] = "First name is required.";
        header("Location: ../views/auth/signup.php");
        exit();
    }

    if (empty($last_name)){
        $_SESSION['error'] = "Last name is required.";
        header("Location: ../views/auth/signup.php");
        exit();
    }

    if (empty($username)){
        $_SESSION['error'] = "Username is required.";
        header("Location: ../views/auth/signup.php");
        exit();
    }

    if (strlen($username) < 4){
        $_SESSION['error'] = "Username must contain at least 4 characters.";
        header("Location: ../views/auth/signup.php");
        exit();
    }

    if (empty($role)){
        $_SESSION['error'] = "Role is required.";
        header("Location: ../views/auth/signup.php");
        exit();
    }

    if (empty($email)){
        $_SESSION['error'] = "Email address is required.";
        header("Location: ../views/auth/signup.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format. Please enter a valid email address.";
        header("Location: ../views/auth/signup.php");
        exit();
    }


    //Code goes here to create a new user in the database
    $user = new User();
    $result = $user->signup($first_name, $last_name, $username, $role, $phone_number, $email, $password);

    if ($result) {
        $_SESSION['success'] = "User created successfully.";
        header("Location: ../views/auth/signup.php");
        exit();
    } else {
        header("Location: ../views/auth/signup.php");
        exit();
    }

}

?>