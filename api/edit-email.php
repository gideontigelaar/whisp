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
$email = strtolower($_POST['email']) ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    sendError('All fields are required.');
}

if (strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendError('Email is not valid.');
}

$stmt = $pdo->prepare("SELECT email FROM users WHERE email = :email");
$stmt->execute(['email' => $email]);
if ($stmt->fetch(PDO::FETCH_ASSOC)) {
    sendError('Email is already taken.');
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password'])) {
    sendError('Invalid password.');
}

$stmt = $pdo->prepare("UPDATE users SET email = :email WHERE user_id = :user_id");
$stmt->execute(['email' => $email, 'user_id' => $user_id]);

sendSuccess();
?>