<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/queries/validate-session.php";

$url = $_SERVER['REQUEST_URI'];
if (strpos($url, '/messages') !== false) {
    $user_id = explode('/', $url)[2];

    if ($user_id) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT * FROM messages WHERE (sender_id = :sender_id AND recipient_id = :recipient_id) OR (sender_id = :recipient_id AND recipient_id = :sender_id)");
        $stmt->execute(['sender_id' => $_SESSION['user_id'], 'recipient_id' => $user_id]);
        $conversation = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$user || !$conversation) {
            header('Location: /messages');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A place for developers to learn and share.">
    <meta name="robots" content="noindex">

    <title>Messages / Whisp</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/fill/style.css">
    <link rel="stylesheet" href="/assets/css/styles.css">

    <script src="/assets/js/index.js"></script>
    <script src="/assets/js/messages.js"></script>

    <style>
        @media (min-width: 576px) {
            .user-container-width {
                max-width: 250px;
            }
        }

        .input-container {
            padding-bottom: 64px;
        }

        @media (min-width: 576px) {
            .input-container {
                padding-bottom: 1rem;
            }
        }

        .message-container-height {
            height: 100dvh;
        }

        @media (min-width: 576px) {
            .message-container-height {
                height: calc(100dvh - 2rem);
            }
        }
    </style>
</head>
<body class="bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-auto ps-0 pe-0 pe-md-3">
                <?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/components/nav.php" ?>
            </div>
            <div class="col p-0 p-sm-3">
                <div class="card-group h-100">
                    <div class="card <?= $user_id ? 'd-none d-md-flex' : 'd-flex' ?> overflow-y-auto user-container-width message-container-height mb-0">
                        <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                            <span class="fs-5">Messages</span>
                            <i class="ph ph-note-pencil" role="button" data-bs-toggle="modal" data-bs-target="#newMessageModal"></i>
                        </div>
                        <?php
                        $stmt = $pdo->prepare("SELECT DISTINCT LEAST(sender_id, recipient_id) AS user1, GREATEST(sender_id, recipient_id) AS user2 FROM messages WHERE sender_id = :user_id OR recipient_id = :user_id");
                        $stmt->execute(['user_id' => $_SESSION['user_id']]);
                        $conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        $latestMessages = [];

                        foreach ($conversations as $conversation) {
                            $stmt = $pdo->prepare("SELECT * FROM messages WHERE (sender_id = :user1 AND recipient_id = :user2) OR (sender_id = :user2 AND recipient_id = :user1) ORDER BY created_at DESC LIMIT 1");
                            $stmt->execute(['user1' => $conversation['user1'], 'user2' => $conversation['user2']]);
                            $message = $stmt->fetch(PDO::FETCH_ASSOC);

                            $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
                            $stmt->execute(['user_id' => $message['sender_id'] == $_SESSION['user_id'] ? $message['recipient_id'] : $message['sender_id']]);
                            $user = $stmt->fetch(PDO::FETCH_ASSOC);

                            $userId = $user['user_id'];
                            $userName = $user['username'];
                            $displayName = $user['display_name'];
                            $profilePicture = $user['profile_picture'] && @getimagesize($user['profile_picture']) ? $user['profile_picture'] : '/assets/images/default-pfp.png';
                            $bio = $user['bio'];
                            $isVerified = $user['is_verified'];
                            $createdAt = date('F Y', strtotime($user['created_at']));
                            $content = $message['content'];

                            $latestMessages[] = [
                                'userId' => $userId,
                                'userName' => $userName,
                                'displayName' => $displayName,
                                'profilePicture' => $profilePicture,
                                'bio' => $bio,
                                'isVerified' => $isVerified,
                                'createdAt' => $createdAt,
                                'content' => $content
                            ];
                        ?>
                        <div class="d-flex align-items-center gap-2 p-3 <?= $user_id == $userId ? 'bg-body-secondary' : '' ?>" role="button" onclick="window.location.href = '/messages/<?= $userId ?>'">
                            <img class="rounded-circle" src="<?= $profilePicture ?>" width="48" height="48" alt="PFP">
                            <div class="d-flex flex-column text-truncate">
                                <div class="d-flex align-items-center gap-1">
                                    <span class="fs-5 text-truncate"><?= $displayName ?></span>
                                    <?php if ($isVerified) { ?>
                                        <i class="ph-fill ph-seal-check text-primary" style="font-size: 22px!important;"></i>
                                    <?php } ?>
                                </div>
                                <span class="opacity-75 text-truncate"><?= $message['sender_id'] == $_SESSION['user_id'] ? 'You: ' : '' ?><?= $content ?></span>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                    </div>

                    <div class="card <?= $user_id ? 'd-flex' : 'd-none d-md-flex' ?> message-container-height mb-0">
                        <?php if ($user_id) { ?>
                            <div class="d-flex align-items-center gap-3 gap-sm-0 sticky-top bg-body p-3 border-bottom" style="border-top-right-radius: 0.375rem;">
                                <div class="d-flex d-sm-none" role="button" onclick="window.history.back()">
                                    <i class="ph ph-arrow-left"></i>
                                </div>
                                <?php
                                $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
                                $stmt->execute(['user_id' => $user_id]);
                                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                                $userName = $user['username'];
                                $displayName = $user['display_name'];
                                ?>
                                <div class="d-flex gap-1 align-items-center text-truncate">
                                    <span class="fs-5 text-truncate"><?= $displayName ?></span>
                                    <span class="opacity-75">@<?= $userName ?></span>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-between overflow-y-auto p-0" id="chat-container">
                                <div class="d-flex flex-column gap-3 px-3 mt-3">
                                    <?php
                                    $profilePicture = $user['profile_picture'] && @getimagesize($user['profile_picture']) ? $user['profile_picture'] : '/assets/images/default-pfp.png';
                                    $bio = $user['bio'];
                                    $isVerified = $user['is_verified'];
                                    $userCreatedAt = date('F Y', strtotime($user['created_at']));

                                    $stmt = $pdo->prepare("SELECT * FROM messages WHERE (sender_id = :sender_id AND recipient_id = :recipient_id) OR (sender_id = :recipient_id AND recipient_id = :sender_id) ORDER BY created_at ASC");
                                    $stmt->execute(['sender_id' => $_SESSION['user_id'], 'recipient_id' => $user_id]);
                                    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    foreach ($messages as $message) {
                                        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
                                        $stmt->execute(['user_id' => $message['sender_id']]);
                                        $sender = $stmt->fetch(PDO::FETCH_ASSOC);

                                        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
                                        $stmt->execute(['user_id' => $message['recipient_id']]);
                                        $recipient = $stmt->fetch(PDO::FETCH_ASSOC);

                                        $senderId = $sender['user_id'];
                                        $senderName = $sender['username'];
                                        $senderDisplayName = $sender['display_name'];
                                        $senderProfilePicture = $sender['profile_picture'] && @getimagesize($sender['profile_picture']) ? $sender['profile_picture'] : '/assets/images/default-pfp.png';
                                        $senderBio = $sender['bio'];
                                        $senderIsVerified = $sender['is_verified'];
                                        $senderCreatedAt = date('F Y', strtotime($sender['created_at']));
                                        $messageCreatedAt = date('H:i', strtotime($message['created_at']));

                                        $recipientId = $recipient['user_id'];
                                        $recipientName = $recipient['username'];
                                        $recipientDisplayName = $recipient['display_name'];
                                        $recipientProfilePicture = $recipient['profile_picture'] && @getimagesize($recipient['profile_picture']) ? $recipient['profile_picture'] : '/assets/images/default-pfp.png';
                                        $recipientBio = $recipient['bio'];
                                        $recipientIsVerified = $recipient['is_verified'];
                                        $recipientCreatedAt = date('F Y', strtotime($recipient['created_at']));

                                        $content = $message['content'];
                                    ?>
                                    <div class="d-flex flex-column gap-1 <?= $message['sender_id'] == $_SESSION['user_id'] ? 'align-items-end' : 'align-items-start' ?>">
                                        <div class="card <?= $message['sender_id'] == $_SESSION['user_id'] ? 'text-bg-primary' : 'text-bg-secondary' ?>" style="max-width: 500px;">
                                            <div class="card-body p-2 <?= $message['sender_id'] == $_SESSION['user_id'] ? 'text-end' : 'text-start' ?>">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="d-flex flex-column">
                                                        <span><?= $content ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="opacity-75 small"><?= $messageCreatedAt ?></span>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <form class="d-flex gap-3 mt-3 pt-3 px-3 sticky-bottom bg-body input-container border-top" method="post" style="border-bottom-right-radius: 0.375rem;">
                                    <textarea class="form-control" id="message-content" rows="1" maxlength="250" placeholder="Message @<?= $userName ?>" style="resize: none;"></textarea>
                                    <button class="btn btn-primary" type="submit" id="message-button" onclick="sendMessage(<?= $user_id ?>)">Send</button>
                                </form>
                            </div>
                        <?php } else { ?>
                            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                <h4 class="card-title">Direct Messages</h4>
                                <p class="card-text opacity-75">Send private messages to other users.</p>
                                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#newMessageModal">Start a conversation</button>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="modal fade" id="newMessageModal" tabindex="-1" aria-labelledby="newMessageModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered align-items-end align-items-sm-start">
                        <div class="modal-content">
                            <form method="post">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="newMessageModalLabel">New message</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="alert alert-danger" id="new-message-button-error" style="opacity: 0; height: 0; margin: 0; padding: 0; white-space: nowrap; overflow: hidden;"></div>

                                    <div class="mb-3">
                                        <label class="form-label" for="recipient-username">Username</label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="basic-addon1">@</span>
                                            <input class="form-control" type="text" id="recipient-username">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="new-message-content">Message</label>
                                        <textarea class="form-control" id="new-message-content" rows="3" maxlength="250" style="resize: none;"></textarea>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                                    <button class="btn btn-primary" type="submit" id="new-message-button" onclick="sendNewMessage()">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-3 col-xxl-2 d-none d-md-flex flex-column p-3 pt-0">
                <?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/components/cards.php" ?>
            </div>
        </div>
    </div>
</body>
</html>