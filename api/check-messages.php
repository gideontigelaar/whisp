<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/validate-session.php";
session_write_close();

function sendResponse($latestMessageId, $unreadCount) {
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode(['latest_message_id' => $latestMessageId, 'unread_count' => $unreadCount]);
    exit();
}

$conversationUserId = $_GET['user_id'] ?? 0;

$stmt = $pdo->prepare("SELECT COALESCE(MAX(message_id), 0) FROM messages WHERE sender_id = :user_id OR recipient_id = :user_id");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$latestMessageId = (int) $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM messages WHERE recipient_id = :user_id AND sender_id != :conversation_user_id AND is_read = 0");
$stmt->execute(['user_id' => $_SESSION['user_id'], 'conversation_user_id' => $conversationUserId]);
$unreadCount = (int) $stmt->fetchColumn();

sendResponse($latestMessageId, $unreadCount);
?>