<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/validate-session.php";

$selectedUserId = $user_id ?? ($_GET['user_id'] ?? null);

$stmt = $pdo->prepare("SELECT m.message_id, m.sender_id, m.content, u.user_id, u.username, u.display_name, u.profile_picture, u.is_verified FROM messages m JOIN (SELECT MAX(message_id) AS message_id FROM messages WHERE sender_id = :user_id OR recipient_id = :user_id GROUP BY LEAST(sender_id, recipient_id), GREATEST(sender_id, recipient_id)) latest ON latest.message_id = m.message_id JOIN users u ON u.user_id = IF(m.sender_id = :user_id, m.recipient_id, m.sender_id) ORDER BY m.message_id DESC");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($conversations as $conversation) {
    $userId = $conversation['user_id'];
    $displayName = $conversation['display_name'];
    $profilePicture = $conversation['profile_picture'] && @getimagesize($conversation['profile_picture']) ? $conversation['profile_picture'] : '/assets/images/default-pfp.png';
    $isVerified = $conversation['is_verified'];
    $content = $conversation['content'];
?>
<div class="d-flex align-items-center gap-2 p-3 conversation <?= $selectedUserId == $userId ? 'bg-body-secondary' : '' ?>" role="button" onclick="window.location.href = '/messages/<?= $userId ?>'" data-message-id="<?= $conversation['message_id'] ?>">
    <img class="rounded-circle" src="<?= $profilePicture ?>" width="48" height="48" alt="PFP">
    <div class="d-flex flex-column text-truncate">
        <div class="d-flex align-items-center gap-1">
            <span class="fs-5 text-truncate"><?= $displayName ?></span>
            <?php if ($isVerified) { ?>
                <i class="ph-fill ph-seal-check text-primary" style="font-size: 22px!important;"></i>
            <?php } ?>
        </div>
        <span class="opacity-75 text-truncate"><?= $conversation['sender_id'] == $_SESSION['user_id'] ? 'You: ' : '' ?><?= $content ?></span>
    </div>
</div>
<?php
}
?>