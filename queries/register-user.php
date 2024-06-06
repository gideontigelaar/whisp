<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/queries/pdo-connect.php";

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

$userName = strtolower($_POST['username']) ?? '';
$displayName = $_POST['username'] ?? '';
$email = strtolower($_POST['email']) ?? '';
$password = $_POST['password'] ?? '';
$passwordConfirm = $_POST['password_confirm'] ?? '';
$inviteCode = $_POST['invite_code'] ?? '';
$bio = 'Hey there, I am a Whisp user!';

if (empty($userName) || empty($email) || empty($password) || empty($passwordConfirm) || empty($inviteCode)) {
    sendError('All fields are required.');
}

if (strlen($userName) < 3 || strlen($userName) > 15 || !ctype_alnum($userName)) {
    sendError('Username must be between 3 and 15 characters long and contain only letters and numbers.');
}

$stmt = $pdo->prepare("SELECT username FROM users WHERE username = :username");
$stmt->execute(['username' => $userName]);
if ($stmt->fetch(PDO::FETCH_ASSOC)) {
    sendError('Username is already taken.');
}

if (strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendError('Email is not valid.');
}

$stmt = $pdo->prepare("SELECT email FROM users WHERE email = :email");
$stmt->execute(['email' => $email]);
if ($stmt->fetch(PDO::FETCH_ASSOC)) {
    sendError('Email is already taken.');
}

if ($password !== $passwordConfirm) {
    sendError('Passwords do not match.');
}

if (strlen($password) > 255) {
    sendError('Password is too long.');
}

if (!preg_match('/^(?=.*[a-z])(?=.*[0-9]).{8,}$/', $password)) {
    sendError('Password must be at least 8 characters long and contain a lowercase letter and a number.');
}

$stmt = $pdo->prepare("SELECT invite_code FROM invites WHERE BINARY invite_code = :inviteCode");
$stmt->execute(['inviteCode' => $inviteCode]);
if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
    sendError('Invalid invite code.');
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (username, display_name, email, password, bio) VALUES (:username, :display_name, :email, :password, :bio)");
$stmt->execute(['username' => $userName, 'display_name' => $displayName, 'email' => $email, 'password' => $hashedPassword, 'bio' => $bio]);

sendSuccess();
?>