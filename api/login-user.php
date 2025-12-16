<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/pdo-connect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/rate-limit.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/totp-encryption.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/libraries/Authenticator.php";

function sendError($message, $statusCode = 400) {
    header('Content-Type: application/json');
    http_response_code($statusCode);
    echo json_encode(['error' => $message]);
    exit();
}

function sendSuccess() {
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode(['success' => true]);
    exit();
}

if (isRateLimited($pdo, 'login', 0.5)) {
    sendError('Too many attempts. Please wait a moment and try again.', 429);
}

$userName = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$totp_code = $_POST['totp_code'] ?? '';

if (empty($userName) || empty($password)) {
    sendError('All fields are required.');
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $userName]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password'])) {
    sendError('Invalid username or password.');
}

$stmt = $pdo->prepare("SELECT secret_key, salt FROM totp_secrets WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user['user_id']]);
$totp_data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($totp_data) {
    if (empty($totp_code)) {
        sendError('TOTP code is required.');
    }

    $totp_secret = decryptTotpSecret($totp_data, $password);

    if ($totp_secret === false) {
        sendError('Failed to decrypt TOTP secret.');
    }

    $authenticator = new Authenticator();
    if (!$authenticator->verifyCode($totp_secret, $totp_code)) {
        sendError('Invalid TOTP code.');
    }

    if (empty($totp_data['salt'])) {
        $encryptedTotp = encryptTotpSecret($totp_secret, $password);
        $stmt = $pdo->prepare("UPDATE totp_secrets SET secret_key = :secret_key, salt = :salt WHERE user_id = :user_id");
        $stmt->execute(['secret_key' => $encryptedTotp['secret_key'], 'salt' => $encryptedTotp['salt'], 'user_id' => $user['user_id']]);
    }
}

$sessionToken = bin2hex(random_bytes(32));
$hashedSessionToken = hash('sha256', $sessionToken);

$stmt = $pdo->prepare("INSERT INTO sessions (session_token, user_id, user_agent, ip_address) VALUES (:session_token, :user_id, :user_agent, :ip_address)");
$stmt->execute(['session_token' => $hashedSessionToken, 'user_id' => $user['user_id'], 'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255), 'ip_address' => $_SERVER['REMOTE_ADDR']]);

$expirationTime = strtotime('+1 month');
setcookie('session_token', $sessionToken, [
    'expires' => $expirationTime,
    'path' => '/',
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true,
    'samesite' => 'Lax'
]);

sendSuccess();
?>