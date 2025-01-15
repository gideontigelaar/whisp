<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/pdo-connect.php";

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

$user_id = $_SESSION['user_id'] ?? '';
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$new_password_confirm = $_POST['new_password_confirm'] ?? '';

if (empty($current_password) || empty($new_password) || empty($new_password_confirm)) {
    sendError('All fields are required.');
}

if ($new_password !== $new_password_confirm) {
    sendError('Passwords do not match.');
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($current_password, $user['password'])) {
    sendError('Current password is not correct.');
}

if ($new_password === $current_password) {
    sendError('New password must be different from the current password.');
}

if (strlen($new_password) > 255) {
    sendError('Password is too long.');
}

if (!preg_match('/^(?=.*[a-z])(?=.*[0-9]).{8,}$/', $new_password)) {
    sendError('Password must be at least 8 characters long and contain a lowercase letter and a number.');
}

$hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE users SET password = :password WHERE user_id = :user_id");
$stmt->execute(['password' => $hashedPassword, 'user_id' => $user_id]);

sendSuccess();
?>