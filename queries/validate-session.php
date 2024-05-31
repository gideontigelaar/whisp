<?php
require_once "pdo-connect.php";

function validateSession($pdo, $sessionToken)
{
    $currentDateTime = date('Y-m-d H:i:s');
    $hashedSessionToken = hash('sha256', $sessionToken);
    $stmt = $pdo->prepare("SELECT * FROM sessions WHERE session_token = :session_token AND expires_at > :current_time");
    $stmt->execute(['session_token' => $hashedSessionToken, 'current_time' => $currentDateTime]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_COOKIE['session_token'])) {
    $sessionData = validateSession($pdo, $_COOKIE['session_token']);
    if ($sessionData && basename($_SERVER['PHP_SELF']) === 'login.php') {
        header('Location: /home');
        exit();
    } elseif (!$sessionData && basename($_SERVER['PHP_SELF']) !== 'login.php') {
        setcookie('session_token', '', time() - 3600, '/');
        header('Location: /');
        exit();
    }
} elseif (basename($_SERVER['PHP_SELF']) !== 'login.php') {
    header('Location: /');
    exit();
}
?>