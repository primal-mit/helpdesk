<?php
    require_once("../../models/Database.php");
    $db = new Database();
    $conn = $db->connect();
    echo "Database connected successfully";
?>