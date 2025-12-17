<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/validate-session.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/rate-limit.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/image-validation.php";

function sendError($message, $statusCode = 400, $data = []) {
    header('Content-Type: application/json');
    http_response_code($statusCode);
    echo json_encode(array_merge(['error' => $message], $data));
    exit();
}

function sendSuccess() {
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode(['success' => true]);
    exit();
}

$content = trim($_POST['content'] ?? '');
$attachments = json_decode($_POST['attachments'] ?? '[]', true);
$replyToPostId = $_POST['reply_to_post_id'] ?? null;
$userId = $_SESSION['user_id'];

if (!is_array($attachments)) {
    sendError('Attachments are not valid.');
}

if (empty($content) && empty($attachments)) {
    sendError('Content is required');
}

if (mb_strlen($content) > 250) {
    sendError('Post is too long.');
}

if (count($attachments) > 4) {
    sendError('You can attach up to 4 images.');
}

$postCooldown = getPostCooldown($pdo, $userId);
if ($postCooldown > 0) {
    sendError('You can only post once per minute.', 429, ['cooldown' => $postCooldown]);
}

if ($replyToPostId) {
    $stmt = $pdo->prepare("SELECT 1 FROM posts WHERE post_id = :post_id");
    $stmt->execute(['post_id' => $replyToPostId]);

    if (!$stmt->fetch()) {
        sendError('The post you are replying to no longer exists.');
    }
}

foreach ($attachments as $attachment) {
    if (!is_string($attachment) || strlen($attachment) > 255 || !isImage($attachment, 5242880, true)) {
        sendError('One of the images is either invalid, or larger than 5MB.');
    }
}

$stmt = $pdo->prepare("INSERT INTO posts (user_id, content, attachments, reply_to_post_id) VALUES (:user_id, :content, :attachments, :reply_to_post_id)");
$stmt->execute(['user_id' => $userId, 'content' => htmlspecialchars($content), 'attachments' => $attachments ? json_encode(array_values($attachments), JSON_UNESCAPED_SLASHES) : null, 'reply_to_post_id' => $replyToPostId ?: null]);

sendSuccess();
?>