<div class="d-flex flex-column gap-1 message <?= $isOwnMessage ? 'align-items-end' : 'align-items-start' ?>" id="message-<?= $message['message_id'] ?>">
    <div class="card <?= $isOwnMessage ? 'text-bg-primary' : 'text-bg-secondary' ?>" style="max-width: 500px;">
        <div class="card-body p-2 <?= $isOwnMessage ? 'text-end' : 'text-start' ?>">
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex flex-column">
                    <span class="text-break" style="white-space: pre-wrap;"><?= $content ?></span>
                </div>
            </div>
        </div>
    </div>
    <span class="opacity-75 small"><?= $messageCreatedAt ?></span>
</div>