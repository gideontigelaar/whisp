<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/queries/pdo-connect.php";

$stmt = $pdo->prepare("SELECT username FROM users WHERE user_id = :user_id");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$userName = $user['username'];

$navLinks = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/nav-links.json"), true);
$currentPage = basename($_SERVER['PHP_SELF']);

function isActiveText($page) {
    global $currentPage;
    return ($currentPage === $page) ? 'active' : 'opacity-75';
}

function isActiveIcon($page) {
    global $currentPage;
    return ($currentPage === $page) ? 'ph-fill' : 'ph';
}
?>
<div class="d-none d-sm-flex sticky-top fs-5 p-sm-3 p-xl-4 flex-column justify-content-between overflow-y-auto card" style="height: 100dvh;">
    <div>
        <a href="/home">
            <img class="d-none d-xl-block pb-3" src="/assets/images/logos/whisp-logo-horizontal.svg" width="180" alt="Whisp logo">
            <img class="d-none d-sm-block d-xl-none pb-3" src="/assets/images/logos/whisp-logo-mark.svg" width="28" alt="Whisp logo">
        </a>
        <hr>
        <ul class="nav flex-column">
            <?php foreach ($navLinks as $link) { ?>
                <li class="nav-item">
                    <a class="nav-link px-0 py-3 text-body <?= isActiveText($link['file']) ?>" href="<?= $link['href'] ?>">
                        <div class="d-flex gap-xl-3 align-items-center">
                            <i class="<?= isActiveIcon($link['file']) ?> <?= $link['icon'] ?>"></i>
                            <div class="d-none d-xl-block"><?= $link['text'] ?></div>
                        </div>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>
    <div>
        <hr>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link px-0 pt-3 text-body <?= isActiveText('settings.php') ?>" href="/settings">
                    <div class="d-flex align-items-center justify-content-xl-between">
                        <div class="d-none d-xl-block text-truncate" style="max-width: 140px;"><?= $userName ?></div>
                        <i class="<?= isActiveIcon('settings.php') ?> ph-gear"></i>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="d-flex d-sm-none fixed-bottom fs-5 px-4 py-1 justify-content-between card">
    <ul class="nav w-100 justify-content-between">
        <?php foreach ($navLinks as $link) { ?>
            <li class="nav-item">
                <a class="nav-link px-0 text-body <?= isActiveText($link['file']) ?>" href="<?= $link['href'] ?>">
                    <div class="d-flex align-items-center">
                        <i class="<?= isActiveIcon($link['file']) ?> <?= $link['icon'] ?>"></i>
                    </div>
                </a>
            </li>
        <?php } ?>
        <li class="nav-item">
            <a class="nav-link px-0 text-body <?= isActiveText('settings.php') ?>" href="/settings">
                <div class="d-flex align-items-center">
                    <i class="<?= isActiveIcon('settings.php') ?> ph-gear"></i>
                </div>
            </a>
        </li>
    </ul>
</div>