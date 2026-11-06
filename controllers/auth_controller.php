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

?>