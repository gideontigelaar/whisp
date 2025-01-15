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
$replyToPostId = $_POST['reply_to_post_id'];
$userId = $_SESSION['user_id'];

if (empty($content)) {
    sendError('Content is required');
}

$stmt = $pdo->prepare("INSERT INTO posts (user_id, content, reply_to_post_id) VALUES (:user_id, :content, :reply_to_post_id)");
$stmt->execute(['user_id' => $userId, 'content' => $content, 'reply_to_post_id' => $replyToPostId]);

sendSuccess();
?>