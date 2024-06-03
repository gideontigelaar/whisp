<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/queries/pdo-connect.php";

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

$content = $_POST['content'];
$userId = $_SESSION['user_id'];

if (empty($content)) {
    sendError('Content is required');
}

$stmt = $pdo->prepare("INSERT INTO posts (user_id, content) VALUES (:user_id, :content)");
$stmt->execute(['user_id' => $userId, 'content' => $content]);

sendSuccess();
?>