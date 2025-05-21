<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/pdo-connect.php";
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

$stmt = $pdo->prepare("SELECT secret_key FROM totp_secrets WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user['user_id']]);
$totp_data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($totp_data) {
    if (empty($totp_code)) {
        sendError('TOTP code is required.');
    }

    $creds = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/creds.json"), true);
    $salt = $creds['salt'];
    $key = hash_pbkdf2('sha256', $password, $salt, 100000, 32, true);

    $encrypted_data = base64_decode($totp_data['secret_key']);
    $iv = substr($encrypted_data, 0, 16);
    $encrypted_secret = substr($encrypted_data, 16);
    $totp_secret = openssl_decrypt($encrypted_secret, 'aes-256-cbc', $key, 0, $iv);

    if ($totp_secret === false) {
        sendError('Failed to decrypt TOTP secret.');
    }

    $authenticator = new Authenticator();
    if (!$authenticator->verifyCode($totp_secret, $totp_code)) {
        sendError('Invalid TOTP code.');
    }
}

$sessionToken = bin2hex(random_bytes(32));
$hashedSessionToken = hash('sha256', $sessionToken);

$stmt = $pdo->prepare("INSERT INTO sessions (session_token, user_id, user_agent, ip_address) VALUES (:session_token, :user_id, :user_agent, :ip_address)");
$stmt->execute(['session_token' => $hashedSessionToken, 'user_id' => $user['user_id'], 'user_agent' => $_SERVER['HTTP_USER_AGENT'], 'ip_address' => $_SERVER['REMOTE_ADDR']]);

$expirationTime = strtotime('+1 month');
setcookie('session_token', $sessionToken, $expirationTime, "/");

sendSuccess();
?>