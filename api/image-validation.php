<?php
function isImage($url, $maxSize = 1048576, $allowGif = false) {
    if (!filter_var($url, FILTER_VALIDATE_URL) || !in_array(strtolower(parse_url($url, PHP_URL_SCHEME)), ['http', 'https'])) {
        return false;
    }

    $context = stream_context_create(['http' => ['timeout' => 5]]);
    $headers = @get_headers($url, true, $context);

    if ($headers === false) {
        return false;
    }

    $headers = array_change_key_case($headers, CASE_LOWER);
    $statusLines = array_filter($headers, 'is_int', ARRAY_FILTER_USE_KEY);
    $statusLine = end($statusLines);
    $mimeType = $headers['content-type'] ?? '';
    $mimeType = strtolower(is_array($mimeType) ? end($mimeType) : $mimeType);
    $imageSize = $headers['content-length'] ?? 0;
    $imageSize = (int) (is_array($imageSize) ? end($imageSize) : $imageSize);

    if (strpos($statusLine, ' 200') === false || strpos($mimeType, 'image/') !== 0) {
        return false;
    }

    if (!$allowGif && strpos($mimeType, 'image/gif') === 0) {
        return false;
    }

    return $imageSize <= $maxSize;
}
?>