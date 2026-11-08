<?php
// Inform client to expect JSON response
header('Content-Type: application/json');

// Include database configuration
require_once 'db.php';

//Ensure only POST requests are processed
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Retrieve inputs from register.html
$username = trim($_POST['username'] ?? '');
$password = ($_POST['password'] ?? '');
$confirm_password = $_POST['confirm_password'] ?? '';

// Backend input validation
if (empty($username) || empty($password) || empty($confirm_password)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

if ($password !== $confirm_password) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters long.']);
    exit;
}

// Check if username already exists
$check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$check_stmt->bind_param("s", $username);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Username already exists.']);
    $check_stmt->close();
    $conn->close();
    exit;
}

$check_stmt->close();

//Hash password using bcrypt
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert new user into database
$insert_user_stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$insert_user_stmt->bind_param("ss", $username, $hashed_password);

if (!$insert_user_stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Error registering user. Please try again later.']);
    $insert_user_stmt->close();
    $conn->close();
    exit;
}

//Retrieve primary key of the newly inserted user
$new_user_id = $conn->insert_id;
$insert_user_stmt->close();

// Initialize empty stats record for the new user
$init_stats_stmt = $conn->prepare("INSERT INTO player_stats (user_id, wins, losses, draws, win_rate) VALUES (?, 0, 0, 0, 0.0)");
$init_stats_stmt->bind_param("i", $new_user_id);
$init_stats_stmt->execute();
$init_stats_stmt->close();

// Close database connection
$conn->close();

echo json_encode(['success' => true, 'message' => 'Registration successful.']);