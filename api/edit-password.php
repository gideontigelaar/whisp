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

$pdo->beginTransaction();

try {
    $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE user_id = :user_id");
    $stmt->execute(['password' => $hashedPassword, 'user_id' => $user_id]);

    $stmt = $pdo->prepare("SELECT secret_key FROM totp_secrets WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $totp_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($totp_data) {
        $creds = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/creds.json"), true);
        $salt = $creds['salt'];

        $old_key = hash_pbkdf2('sha256', $current_password, $salt, 100000, 32, true);
        $encrypted_data = base64_decode($totp_data['secret_key']);
        $iv = substr($encrypted_data, 0, 16);
        $encrypted_secret = substr($encrypted_data, 16);
        $totp_secret = openssl_decrypt($encrypted_secret, 'aes-256-cbc', $old_key, 0, $iv);

        if ($totp_secret === false) {
            $pdo->rollBack();
            sendError('Failed to decrypt TOTP secret with current password.');
        }

        $new_key = hash_pbkdf2('sha256', $new_password, $salt, 100000, 32, true);
        $new_iv = openssl_random_pseudo_bytes(16);
        $new_encrypted_secret = openssl_encrypt($totp_secret, 'aes-256-cbc', $new_key, 0, $new_iv);
        $new_encrypted_data = base64_encode($new_iv . $new_encrypted_secret);

        $stmt = $pdo->prepare("UPDATE totp_secrets SET secret_key = :secret_key WHERE user_id = :user_id");
        $stmt->execute(['secret_key' => $new_encrypted_data, 'user_id' => $user_id]);
    }

    $pdo->commit();
    sendSuccess();

} catch (Exception $e) {
    $pdo->rollBack();
    sendError('An error occurred while updating your password. Please try again.');
}
?>