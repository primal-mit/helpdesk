<?php
session_start();
require_once '../models/Database.php';
require_once '../models/Ticket.php';


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




if (isset($_POST['update_user_submit'])) {
    $user_id = $_POST['user_id'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $role = $_POST['role'];
    $status = $_POST['status'];         
    $phone_number = trim($_POST['phone_number']);
    $email = trim($_POST['email']);

    //Serverside validation
    if (empty($first_name)){
        $_SESSION['error'] = "First name is required.";
        header("Location: ../views/user/edit-user.php?id=$user_id");
        exit();
    }

    if (empty($last_name)){
        $_SESSION['error'] = "Last name is required.";
        header("Location: ../views/user/edit-user.php?id=$user_id");
        exit();
    }

    if (empty($username)){
        $_SESSION['error'] = "Username is required.";
        header("Location: ../views/user/edit-user.php?id=$user_id");
        exit();
    }

    if (strlen($username) < 4){
        $_SESSION['error'] = "Username must contain at least 4 characters.";
        header("Location: ../views/user/edit-user.php?id=$user_id");
        exit();
    }
    
    if (empty($role)){
        $_SESSION['error'] = "Role is required.";
        header("Location: ../views/user/edit-user.php?id=$user_id");
        exit();
    }

    if (empty($email)){
        $_SESSION['error'] = "Email address is required.";
        header("Location: ../views/user/edit-user.php?id=$user_id");
        exit();
    }
    
    if (empty($phone_number)){
        $_SESSION['error'] = "Phone number is required.";
        header("Location: ../views/user/edit-user.php?id=$user_id");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format. Please enter a valid email address.";
        header("Location: ../views/user/edit-user.php?id=$user_id");
        exit();
    }

    // Code goes here to update the user in the database
    $user = new User();
    $result = $user->update($user_id, $first_name, $last_name, $username, $role, $status, $phone_number, $email);

    if ($result) {
        $_SESSION['success'] = "User updated successfully.";
        header("Location: ../views/user/edit-user.php?id=$user_id");
        exit();
    } else {
        header("Location: ../views/user/edit-user.php?id=$user_id");
        exit();
    }
}


if(isset($_POST['delete_user_submit'])) {
    $user_id = $_POST['user_id'];

    // Code goes here to delete the user from the database
    $user = new User();
    $result = $user->delete($user_id);

    if ($result) {
        $_SESSION['success'] = "User deleted successfully.";
        header("Location: ../views/user/all-users.php");
        exit();
    } else {
        $_SESSION['error'] = "An error occurred while deleting the user. Please try again.";
        header("Location: ../views/user/manage-users.php");
        exit();
    }
}

if(isset($_POST['change_password_submit'])) {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate new password and confirm password match
    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "New password and confirm password do not match.";
        header("Location: ../views/user/change-password.php");
        exit();
    }

    // Code goes here to change the user's password in the database
    $user = new User();
    $result = $user->changePassword($user_id, $current_password, $new_password);

    if ($result) {
        $_SESSION['success'] = "Password changed successfully.";
        header("Location: ../views/user/change-password.php");
        exit();
    } else {
        header("Location: ../views/user/change-password.php");
        exit();
    }
}

?>