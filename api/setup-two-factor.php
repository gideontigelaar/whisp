<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/validate-session.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/totp-encryption.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/libraries/Authenticator.php";

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
$totp_code = $_POST['totp_code'] ?? '';
$totp_secret = $_POST['totp_secret'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($user_id) || empty($totp_code) || empty($totp_secret) || empty($password)) {
    sendError('Missing required parameters');
}

$stmt = $pdo->prepare("SELECT password FROM users WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password'])) {
    sendError('Invalid password');
}

$authenticator = new Authenticator();

if (!$authenticator->verifyCode($totp_secret, $totp_code)) {
    sendError('Invalid TOTP code');
}

$stmt = $pdo->prepare("SELECT 1 FROM totp_secrets WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$exists = $stmt->fetch();

if ($exists) {
    sendError('2FA is already set up.');
}

$encryptedTotp = encryptTotpSecret($totp_secret, $password);

$stmt = $pdo->prepare("INSERT INTO totp_secrets (user_id, secret_key, salt) VALUES (:user_id, :secret_key, :salt)");
$stmt->execute(['user_id' => $user_id, 'secret_key' => $encryptedTotp['secret_key'], 'salt' => $encryptedTotp['salt']]);

sendSuccess();
?>