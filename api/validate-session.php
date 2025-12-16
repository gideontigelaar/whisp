<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/pdo-connect.php";

function validateSession($pdo, $sessionToken)
{
    $currentDateTime = date('Y-m-d H:i:s');
    $hashedSessionToken = hash('sha256', $sessionToken);
    $stmt = $pdo->prepare("SELECT user_id FROM sessions WHERE session_token = :session_token AND expires_at > :current_time");
    $stmt->execute(['session_token' => $hashedSessionToken, 'current_time' => $currentDateTime]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function denyAccess()
{
    unset($_SESSION['user_id']);

    if (strpos($_SERVER['PHP_SELF'], '/pages/') === 0) {
        header('Location: /');
    } else {
        header('Content-Type: application/json');
        http_response_code(401);
        echo json_encode(['error' => 'Your session has expired. Please log in again.']);
    }
    exit();
}

$isLoginPage = basename($_SERVER['PHP_SELF']) === 'login.php';

if (isset($_COOKIE['session_token'])) {
    $validSessionData = validateSession($pdo, $_COOKIE['session_token']);
    if ($validSessionData) {
        $_SESSION['user_id'] = $validSessionData['user_id'];
        if ($isLoginPage) {
            header('Location: /home');
            exit();
        }
    } elseif (!$isLoginPage) {
        setcookie('session_token', '', time() - 3600, '/');
        denyAccess();
    }
} elseif (!$isLoginPage) {
    denyAccess();
}
?>