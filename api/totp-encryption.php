<?php
function deriveTotpKey($password, $salt) {
    return hash_pbkdf2('sha256', $password, $salt, 100000, 32, true);
}

function encryptTotpSecret($totpSecret, $password) {
    $salt = random_bytes(16);
    $iv = random_bytes(16);
    $key = deriveTotpKey($password, $salt);
    $encryptedSecret = openssl_encrypt($totpSecret, 'aes-256-cbc', $key, 0, $iv);

    return [
        'secret_key' => base64_encode($iv . $encryptedSecret),
        'salt' => base64_encode($salt)
    ];
}

function decryptTotpSecret($totpData, $password) {
    if (empty($totpData['salt'])) {
        $creds = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/creds.json"), true);
        $salt = $creds['salt'] ?? '';
    } else {
        $salt = base64_decode($totpData['salt']);
    }

    $key = deriveTotpKey($password, $salt);
    $encryptedData = base64_decode($totpData['secret_key']);
    $iv = substr($encryptedData, 0, 16);
    $encryptedSecret = substr($encryptedData, 16);

    return openssl_decrypt($encryptedSecret, 'aes-256-cbc', $key, 0, $iv);
}
?>