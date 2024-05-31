<?php
require_once "pdo-connect.php";

function sendError($message) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['error' => $message]);
    exit();
}

function sendSuccess() {
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode(['success' => true]);
    exit();
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    sendError('All fields are required.');
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password'])) {
    sendError('Invalid username or password.');
}

$sessionToken = bin2hex(random_bytes(32));
$hashedSessionToken = hash('sha256', $sessionToken);

$stmt = $pdo->prepare("INSERT INTO sessions (user_id, session_token) VALUES (:user_id, :session_token)");
$stmt->execute(['user_id' => $user['user_id'], 'session_token' => $hashedSessionToken]);

$expirationTime = strtotime('+1 month');
setcookie('session_token', $sessionToken, $expirationTime, "/");

sendSuccess();
?>