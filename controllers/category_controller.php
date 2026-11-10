<?php
session_start();
require_once '../models/Database.php';
require_once '../models/Category.php';


//Create Category
if (isset($_POST['create_category_submit'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    //Serverside validation
    if (empty($name)){
        $_SESSION['error'] = "Category name is required.";
        header("Location: ../views/category/create-category.php");
        exit();
    }

    //Code goes here to create a new category in the database
    $category = new Category();
    $result = $category->create($name, $description);

    if ($result) {
        $_SESSION['success'] = "Category created successfully.";
        header("Location: ../views/category/create-category.php");
        exit();
    } else {
        $_SESSION['error'] = "An error occurred while creating the category. Please try again.";
        header("Location: ../views/category/create-category.php");
        exit();
    }
}


if (isset($_POST['update_category_submit'])) {
    $category_id = $_POST['category_id'];
    $name = trim($_POST['name']);
    $description = trim($_POST['description']); 

    //Serverside validation
    if (empty($name)){
        $_SESSION['error'] = "Category name is required.";
        header("Location: ../views/category/edit-category.php?id=$category_id");
        exit();
    }

    // Code goes here to update the category in the database
    $category = new Category();
    $result = $category->update($category_id, $name, $description);

    if ($result) {
        $_SESSION['success'] = "Category updated successfully.";
        header("Location: ../views/category/edit-category.php?id=$category_id");
        exit();
    } else {
        $_SESSION['error'] = "An error occurred while updating the category. Please try again.";    
        header("Location: ../views/category/edit-category.php?id=$category_id");
        exit();
    }
}


if(isset($_POST['delete_category_submit'])) {
    $category_id = $_POST['category_id'];

    // Code goes here to delete the category from the database
    $category = new Category();
    $result = $category->delete($category_id);

    if ($result) {
        $_SESSION['success'] = "Category deleted successfully.";
        header("Location: ../views/category/manage-categories.php");
        exit();
    } else {
        $_SESSION['error'] = "An error occurred while deleting the category. Please try again.";
        header("Location: ../views/category/manage-categories.php");
        exit();
    }
}

?>