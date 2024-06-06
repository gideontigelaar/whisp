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

$user_id = $_POST['user_id'] ?? '';
$display_name = $_POST['display_name'] ?? '';
$profile_picture = $_POST['profile_picture'] ?? '';
$bio = $_POST['bio'] ?? '';

if (empty($user_id) || empty($display_name) || empty($profile_picture) || empty($bio)) {
    sendError('All fields are required.');
}

if (strlen($display_name) > 50) {
    sendError('Display name is too long.');
}

if (strlen($profile_picture) > 255) {
    sendError('Profile picture URL is too long.');
}

if (strlen($bio) > 150) {
    sendError('Bio is too long.');
}

$stmt = $pdo->prepare("UPDATE users SET display_name = :display_name, profile_picture = :profile_picture, bio = :bio WHERE user_id = :user_id");
$stmt->execute(['display_name' => $display_name, 'profile_picture' => $profile_picture, 'bio' => $bio, 'user_id' => $user_id]);

sendSuccess();
?>