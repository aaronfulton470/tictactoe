<?php
session_start();
header('Content-Type: application/json');
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'No active session. Guest match not recorded.'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
    exit;
}

$raw_input = file_get_contents('php://input');
$json_data = json_decode($raw_input, true);
$result = $json_data['result'] ?? $_POST['result'] ?? '';

$allowed_outcomes = ['win', 'loss', 'draw'];
if (!in_array($result, $allowed_outcomes, true)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid match result.'
    ]);
    exit;
}

$user_id = (int) $_SESSION['user_id'];

$select_stmt = $conn->prepare("SELECT wins, losses, draws FROM user_stats WHERE user_id = ?");
$select_stmt->bind_param('i', $user_id);
$select_stmt->execute();
$query_result = $select_stmt->get_result();

if ($query_result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Player stats not found.']);
        $select_stmt->close();
        $conn->close();
    exit;
}

$current_stats = $query_result->fetch_assoc();
$select_stmt->close();

$wins = (int) $current_stats['wins'];
$losses = (int) $current_stats['losses'];
$draws = (int) $current_stats['draws'];

if ($result === 'win') {
    $wins++;
} elseif ($result === 'loss') {
    $losses++;
} else {
    $draws++;
}

$total_games = $wins + $losses + $draws;
$win_rate = ($total_games > 0) ? round(($wins / $total_games) * 100, 1) : 0.0;

$update_stmt = $conn->prepare("UPDATE user_stats SET wins = ?, losses = ?, draws = ?, win_rate ? WHERE user_id = ?");
$update_stmt->bind_param("iiidi", $wins, $losses, $draws, $win_rate, $user_id);

if ($update_stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Match results successfully recorded.',
        'updated' => [
            'result' => $result,
            'wins' => $wins,
            'losses' => $losses,
            'draws' => $draws,
            'win_rate' => $win_rate,
            'total_games' => $total_games
        ]
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to update database.'
    ]);
}
$update_stmt->close();
$conn->close();