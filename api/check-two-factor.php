<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/pdo-connect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/rate-limit.php";

function sendResponse($hasTwoFactor) {
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode(['has_two_factor' => $hasTwoFactor]);
    exit();
}

function sendError($message, $statusCode = 400) {
    header('Content-Type: application/json');
    http_response_code($statusCode);
    echo json_encode(['error' => $message]);
    exit();
}

if (isRateLimited($pdo, 'check-two-factor', 0.5)) {
    sendError('Too many attempts. Please wait a moment and try again.', 429);
}

$userName = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($userName) || empty($password)) {
    sendError('Username and password are required.');
}

$stmt = $pdo->prepare("SELECT user_id, password FROM users WHERE username = :username");
$stmt->execute(['username' => $userName]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password'])) {
    sendError('Invalid username or password.');
}

$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM totp_secrets WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user['user_id']]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$hasTwoFactor = $result['count'] > 0;
sendResponse($hasTwoFactor);
?>