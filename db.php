<?php
//Central Database Configuration File
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "login_db";

//Establishing Connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

//Verify database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Set character encoding to UTF-8
$conn->set_charset("utf8mb4");