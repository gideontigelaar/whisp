<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/api/validate-session.php";

$url = $_SERVER['REQUEST_URI'];
if (strpos($url, '/messages') !== false) {
    $user_id = (int) (explode('/', $url)[2] ?? 0);

    if ($user_id) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT 1 FROM messages WHERE (sender_id = :sender_id AND recipient_id = :recipient_id) OR (sender_id = :recipient_id AND recipient_id = :sender_id) LIMIT 1");
        $stmt->execute(['sender_id' => $_SESSION['user_id'], 'recipient_id' => $user_id]);
        $conversation = $stmt->fetch(PDO::FETCH_ASSOC);

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

    <title>Messages / Whisp</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/regular/style.css" integrity="sha384-6p9AefaqUhEVheRlj1mpAkbngHXy9mbYMrIdcIt4Jlc9lOLIablJq3bBsLOjGwZ7" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2/src/fill/style.css" integrity="sha384-pPVoXE8ft+zxKtxIDDI7SfTK6y95NHm4qa+hKEg/hs8VkjW5IP+9/dGOPCbDpUPl" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/styles.css">

    <script src="/assets/js/index.js"></script>
    <script src="/assets/js/messages.js"></script>

    <style>
        @media (min-width: 576px) {
            .user-container-width {
                max-width: 200px;
            }
        }

        @media (min-width: 768px) {
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
                    <div class="card <?= $user_id ? 'd-none d-sm-flex' : 'd-flex' ?> overflow-y-auto user-container-width message-container-height mb-0">
                        <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                            <span class="fs-5">Messages</span>
                            <i class="ph ph-note-pencil" role="button" data-bs-toggle="modal" data-bs-target="#newMessageModal"></i>
                        </div>
                        <div id="conversations-container">
                            <?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/load-conversations.php" ?>
                        </div>
                    </div>

                    <div class="card <?= $user_id ? 'd-flex' : 'd-none d-sm-flex' ?> message-container-height mb-0">
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
                            <div class="card-body d-flex flex-column justify-content-between overflow-y-auto p-0" id="chat-container" data-user-id="<?= $user_id ?>">
                                <div class="d-flex flex-column gap-3 px-3 mt-3" id="messages-container">
                                    <?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/load-messages.php" ?>
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

                <div class="modal fade" id="newMessageModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="newMessageModalLabel" aria-hidden="true">
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