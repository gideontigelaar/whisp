<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/pdo-connect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/validate-session.php";

function sendSuccess() {
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode(['success' => true]);
    exit();
}

$user_id = $_SESSION['user_id'] ?? null;

$stmt = $pdo->prepare("SELECT 1 FROM totp_secrets WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("DELETE FROM totp_secrets WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);

sendSuccess();
?>