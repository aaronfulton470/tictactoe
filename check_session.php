<?php
// Resume active session
session_start();

//Set response type to JSON
header('Content-Type: application/json');

//Return session status as JSON
echo json_encode([
    'logged_in' => isset($_SESSION['user_id']),
    'username' => $_SESSION['username'] ?? ''
]);