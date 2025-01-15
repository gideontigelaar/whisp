<?php
session_start();
try {
    $creds = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/creds.json"), true);
    $dbhost = $creds['dbhost'];
    $dbname = $creds['dbname'];
    $dbuser = $creds['dbuser'];
    $dbpass = $creds['dbpass'];

    $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo "An error occurred. Please try again later.";
    exit();
}

function validateSession($pdo, $sessionToken)
{
    $currentDateTime = date('Y-m-d H:i:s');
    $hashedSessionToken = hash('sha256', $sessionToken);
    $stmt = $pdo->prepare("SELECT user_id FROM sessions WHERE session_token = :session_token AND expires_at > :current_time");
    $stmt->execute(['session_token' => $hashedSessionToken, 'current_time' => $currentDateTime]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_COOKIE['session_token'])) {
    $validSessionData = validateSession($pdo, $_COOKIE['session_token']);
    if ($validSessionData) {
        $_SESSION['user_id'] = $validSessionData['user_id'];
        if (basename($_SERVER['PHP_SELF']) === 'login.php') {
            header('Location: /home');
            exit();
        }
    } elseif (basename($_SERVER['PHP_SELF']) !== 'login.php') {
        setcookie('session_token', '', time() - 3600, '/');
        header('Location: /');
        exit();
    }
} elseif (basename($_SERVER['PHP_SELF']) !== 'login.php') {
    header('Location: /');
    exit();
}
?>