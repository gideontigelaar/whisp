<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/validate-session.php";
session_write_close();

function sendResponse($latestMessageId) {
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode(['latest_message_id' => $latestMessageId]);
    exit();
}

$stmt = $pdo->prepare("SELECT COALESCE(MAX(message_id), 0) FROM messages WHERE sender_id = :user_id OR recipient_id = :user_id");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$latestMessageId = (int) $stmt->fetchColumn();

sendResponse($latestMessageId);
?>