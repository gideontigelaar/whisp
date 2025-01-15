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

$content = htmlspecialchars($_POST['content']);
$recipientId = $_POST['recipient_id'];
$senderId = $_SESSION['user_id'];

if (empty($content)) {
    sendError('Content is required');
}

$stmt = $pdo->prepare("INSERT INTO messages (sender_id, recipient_id, content) VALUES (:sender_id, :recipient_id, :content)");
$stmt->execute(['sender_id' => $senderId, 'recipient_id' => $recipientId, 'content' => $content]);

sendSuccess();
?>