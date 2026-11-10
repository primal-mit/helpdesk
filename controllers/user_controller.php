<?php
session_start();
require_once '../models/Database.php';
require_once '../models/User.php';


//Create User
if (isset($_POST['create_user_submit'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $role = $_POST['role'];
    $status = $_POST['status'];         
    $phone_number = trim($_POST['phone_number']);
    $email = trim($_POST['email']);
    $password = $username; // Set password to username for initial login    

    //Serverside validation
    if (empty($first_name)){
        $_SESSION['error'] = "First name is required.";
        header("Location: ../views/user/create-user.php");
        exit();
    }

    if (empty($last_name)){
        $_SESSION['error'] = "Last name is required.";
        header("Location: ../views/user/create-user.php");
        exit();
    }

    if (empty($username)){
        $_SESSION['error'] = "Username is required.";
        header("Location: ../views/user/create-user.php");
        exit();
    }

    if (strlen($username) < 4){
        $_SESSION['error'] = "Username must contain at least 4 characters.";
        header("Location: ../views/user/create-user.php");
        exit();
    }
    
    if (empty($role)){
        $_SESSION['error'] = "Role is required.";
        header("Location: ../views/user/create-user.php");
        exit();
    }

    if (empty($email)){
        $_SESSION['error'] = "Email address is required.";
        header("Location: ../views/user/create-user.php");
        exit();
    }
    
    if (empty($phone_number)){
        $_SESSION['error'] = "Phone number is required.";
        header("Location: ../views/user/create-user.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format. Please enter a valid email address.";
        header("Location: ../views/user/create-user.php");
        exit();
    }


    //Code goes here to create a new user in the database
    $user = new User();
    $result = $user->create($first_name, $last_name, $username, $role, $status, $phone_number, $email);

    if ($result) {
        $_SESSION['success'] = "User created successfully.";
        header("Location: ../views/user/create-user.php");
        exit();
    } else {
        header("Location: ../views/user/create-user.php");
        exit();
    }
}



?>