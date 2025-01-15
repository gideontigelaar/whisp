<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/pdo-connect.php";

$stmt = $pdo->prepare("DELETE FROM sessions WHERE session_token = :session_token");
$stmt->execute(['session_token' => hash('sha256', $_COOKIE['session_token'])]);

setcookie('session_token', '', time() - 3600, '/');
session_destroy();
header('Location: /');
exit();
?>