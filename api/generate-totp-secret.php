<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/pdo-connect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/libraries/Authenticator.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/libraries/QRCode.php";

function sendSuccess($secret, $base64) {
    header('Content-Type: application/json');
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'secret' => $secret,
        'qrCode' => 'data:image/png;base64,' . $base64
    ]);
    exit();
}

$stmt = $pdo->prepare("SELECT email FROM users WHERE user_id = :user_id");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$authenticator = new Authenticator();
$secret = $authenticator->createSecret();

$label = 'Whisp: ' . $user['email'];
$url = 'otpauth://totp/' . urlencode($label) . '?secret=' . urlencode($secret) . '&issuer=Whisp';

$qrCode = new QRCode($url);
$image = $qrCode->render_image();

ob_start();
imagepng($image);
$imageData = ob_get_clean();
imagedestroy($image);

$base64 = base64_encode($imageData);

sendSuccess($secret, $base64);
?>