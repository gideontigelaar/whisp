<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/validate-session.php";

$stmt = $pdo->prepare("DELETE FROM sessions WHERE user_id = :user_id AND session_token != :session_token");
$stmt->execute(['user_id' => $_SESSION['user_id'], 'session_token' => hash('sha256', $_COOKIE['session_token'])]);

header('Location: /');
exit();
?>