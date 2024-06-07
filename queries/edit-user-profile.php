<?php
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

$userId = $_POST['user_id'] ?? '';
$displayName = $_POST['display_name'] ?? '';
$profilePicture = $_POST['profile_picture'] ?? '';
$bio = $_POST['bio'] ?? '';

if (empty($userId) || empty($displayName) || empty($bio)) {
    sendError('All fields, except profile picture, are required.');
}

if (strlen($displayName) > 50) {
    sendError('Display name is too long.');
}

if (strlen($profilePicture) > 255) {
    sendError('Profile picture URL is too long.');
}

if (!empty($profilePicture) && !@getimagesize($profilePicture)) {
    sendError('Profile picture URL is invalid.');
}

if (strlen($bio) > 150) {
    sendError('Bio is too long.');
}

$stmt = $pdo->prepare("UPDATE users SET display_name = :display_name, profile_picture = :profile_picture, bio = :bio WHERE user_id = :user_id");
$stmt->execute(['display_name' => $displayName, 'profile_picture' => $profilePicture, 'bio' => $bio, 'user_id' => $userId]);

sendSuccess();
?>