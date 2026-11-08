<?php
//Start session
session_start();

//Tell browser to expect a JSON response
header('Content-Type: application/json');

//Include database connection
require_once 'db.php';

//Ensure POST is request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

//Read inputs from form
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

//Input validation
if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Username and password are required.']);
    exit;
}

//Query users table for account
$stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

//Check if user exists
if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
        //Regenerate session ID
        session_regenerate_id(true);

        //Store user ID in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        echo json_encode(['success' => true, 'message' => 'Login successful.' . htmlspecialchars($user['username']) . '.']);
        $stmt->close();
        $conn->close();
        exit;
    }
}

//Return error if login fails
echo json_encode(['success' => false, 'message' => 'Login Failed.']);
$stmt->close();
$conn->close();
