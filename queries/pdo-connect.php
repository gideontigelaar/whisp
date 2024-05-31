<?php
$cred = file_get_contents('../creds.json');
$cred = json_decode($cred, true);
$dbhost = $cred['dbhost'];
$dbname = $cred['dbname'];
$dbuser = $cred['dbuser'];
$dbpass = $cred['dbpass'];

try {
    $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed";
}
?>