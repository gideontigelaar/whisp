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

function isImage($url) {
    $headers = get_headers($url, 1);

    if ($headers !== false && strpos($headers[0], '200') !== false) {
        $mime_type = $headers["Content-Type"];

        if (strpos($mime_type, "image/") === 0 && $mime_type !== "image/gif") {
            $image_size = (int)$headers["Content-Length"];

            if ($image_size <= 1048576) {
                return true;
            }
        }
    }
    return false;
}

$userId = $_SESSION['user_id'];
$displayName = htmlspecialchars($_POST['display_name']) ?? '';
$profilePicture = $_POST['profile_picture'] ?? '';
$bio = htmlspecialchars($_POST['bio']) ?? '';

if (empty($userId) || empty($displayName) || empty($bio)) {
    sendError('All fields, except profile picture, are required.');
}

if (strlen($displayName) > 50) {
    sendError('Display name is too long.');
}

if (strlen($profilePicture) > 255) {
    sendError('Profile picture URL is too long.');
}

if (!empty($profilePicture) && !isImage($profilePicture)) {
    sendError('Profile picture is either invalid, or larger than 1MB.');
}

if (strlen($bio) > 150) {
    sendError('Bio is too long.');
}

$stmt = $pdo->prepare("UPDATE users SET display_name = :display_name, profile_picture = :profile_picture, bio = :bio WHERE user_id = :user_id");
$stmt->execute(['display_name' => $displayName, 'profile_picture' => $profilePicture, 'bio' => $bio, 'user_id' => $userId]);

sendSuccess();
?>