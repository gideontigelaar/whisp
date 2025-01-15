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
$username = strtolower($_POST['username']) ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    sendError('All fields are required.');
}

if (strlen($username) < 3 || strlen($username) > 15 || !ctype_alnum($username)) {
    sendError('Username must be between 3 and 15 characters long and contain only letters and numbers.');
}

$stmt = $pdo->prepare("SELECT username FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
if ($stmt->fetch(PDO::FETCH_ASSOC)) {
    sendError('Username is already taken.');
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password'])) {
    sendError('Invalid password.');
}

$stmt = $pdo->prepare("UPDATE users SET username = :username WHERE user_id = :user_id");
$stmt->execute(['username' => $username, 'user_id' => $user_id]);

sendSuccess();
?>