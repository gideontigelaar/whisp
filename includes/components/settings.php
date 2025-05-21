
<?php
$stmt = $pdo->prepare("SELECT 1 FROM totp_secrets WHERE user_id = :user_id");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$hasTotpSecret = $stmt->fetchColumn() ? true : false;
?>
<div class="modal fade" id="editSettingsModal" tabindex="-1" aria-labelledby="editSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered align-items-end align-items-sm-start">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editSettingsModalLabel">Settings</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3 d-flex gap-2">
                    <div>
                        <input type="radio" class="btn-check theme-btn" name="theme-options" id="dark" autocomplete="off">
                        <label class="btn bg-black text-white theme-btn-label border border-2" for="dark">Dark Mode</label>
                    </div>

                    <div>
                        <input type="radio" class="btn-check theme-btn" name="theme-options" id="light" autocomplete="off">
                        <label class="btn bg-white text-black theme-btn-label border border-2" for="light">Light Mode</label>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column">
                            <span>Username</span>
                            <span class="opacity-75 small">@<?= $currentUserName ?></span>
                        </div>
                        <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editUsernameModal">Edit</button>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column">
                            <span>Email</span>
                            <span class="opacity-75 small"><?= $currentUserEmail ?></span>
                        </div>
                        <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editEmailModal">Edit</button>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column">
                            <span>Password</span>
                            <span class="opacity-75 small">••••••••••••••••</span>
                        </div>
                        <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editPasswordModal">Edit</button>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column">
                            <span>Two-factor authentication</span>
                            <span class="opacity-75 small"><?= $hasTotpSecret ? '<span class="text-success">Enabled</span>' : '<span class="text-danger">Disabled</span>' ?></span>
                        </div>
                        <?php if ($hasTotpSecret) { ?>
                            <button class="btn btn-danger" type="button" onclick="disableTwoFactor()">Disable</button>
                        <?php } else { ?>
                            <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#twoFactorModal">Edit</button>
                        <?php } ?>
                    </div>
                </div>

                <div class="mb-3">
                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#manageSessionsModal">Manage sessions</button>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <div class="d-flex gap-2">
                    <button class="btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">Delete account</button>
                    <button class="btn btn-secondary" type="button" id="logout-button" onclick="logoutUser()">Log out</button>
                </div>

                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editUsernameModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="editUsernameModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered align-items-end align-items-sm-start">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editUsernameModalLabel">Edit username</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-danger" id="edit-username-button-error" style="opacity: 0; height: 0; margin: 0; padding: 0; white-space: nowrap; overflow: hidden;"></div>

                    <div class="mb-3">
                        <label class="form-label" for="edit-username">Username</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">@</span>
                            <input class="form-control" type="text" id="edit-username" autocomplete="username" value="<?= $currentUserName ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="edit-username-password">Current password</label>
                        <input class="form-control" type="password" id="edit-username-password" autocomplete="current-password">
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editSettingsModal">Cancel</button>
                    <button class="btn btn-primary" type="submit" id="edit-username-button" onclick="editUsername()">Update username</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editEmailModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="editEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered align-items-end align-items-sm-start">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editEmailModalLabel">Edit email</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-danger" id="edit-email-button-error" style="opacity: 0; height: 0; margin: 0; padding: 0; white-space: nowrap; overflow: hidden;"></div>

                    <div class="mb-3">
                        <label class="form-label" for="edit-email">Email</label>
                        <input class="form-control" type="email" id="edit-email" autocomplete="email" value="<?= $currentUserEmail ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="edit-email-password">Current password</label>
                        <input class="form-control" type="password" id="edit-email-password" autocomplete="current-password">
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editSettingsModal">Cancel</button>
                    <button class="btn btn-primary" type="submit" id="edit-email-button" onclick="editEmail()">Update email</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editPasswordModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="editPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered align-items-end align-items-sm-start">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editPasswordModalLabel">Edit password</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-danger" id="edit-password-button-error" style="opacity: 0; height: 0; margin: 0; padding: 0; white-space: nowrap; overflow: hidden;"></div>

                    <div class="mb-3">
                        <label class="form-label" for="current-password">Current password</label>
                        <input class="form-control" type="password" id="current-password" autocomplete="current-password">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="edit-password">New password</label>
                        <input class="form-control" type="password" id="edit-password" autocomplete="new-password">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="edit-password-confirm">Confirm new password</label>
                        <input class="form-control" type="password" id="edit-password-confirm" autocomplete="new-password">
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="show-passwords" onclick="showPasswords()">
                            <label class="form-check-label" for="show-passwords">Show passwords</label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editSettingsModal">Cancel</button>
                    <button class="btn btn-primary" type="submit" id="edit-password-button" onclick="editPassword()">Update password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteAccountModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered align-items-end align-items-sm-start">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteAccountModalLabel">Delete account</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="alert alert-danger" id="delete-account-button-error" style="opacity: 0; height: 0; margin: 0; padding: 0; white-space: nowrap; overflow: hidden;"></div>

                    <div class="mb-3">
                        <label class="form-label" for="delete-account-password">Password</label>
                        <input class="form-control" type="password" id="delete-account-password" autocomplete="current-password">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="delete-account-confirm">Confirm account deletion</label>
                        <input class="form-control" type="text" id="delete-account-confirm" autocomplete="off" placeholder="Type 'DELETE' to confirm">
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editSettingsModal">Cancel</button>
                    <button class="btn btn-danger" type="submit" id="delete-account-button" onclick="deleteAccount()">Delete account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="manageSessionsModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="manageSessionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered align-items-end align-items-sm-start">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="manageSessionsModalLabel">Active sessions</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body d-flex flex-column gap-3">
                <?php
                $currentSessionId = hash('sha256', $_COOKIE['session_token']);
                $currentSession = $pdo->prepare("SELECT * FROM sessions WHERE session_token = :session_token");
                $currentSession->execute(['session_token' => $currentSessionId]);
                $currentSessionData = $currentSession->fetch(PDO::FETCH_ASSOC);

                $stmt = $pdo->prepare("SELECT * FROM sessions WHERE user_id = :user_id AND expires_at > :current_time ORDER BY created_at DESC");
                $stmt->execute(['user_id' => $_SESSION['user_id'], 'current_time' => date('Y-m-d H:i:s')]);
                $activeSessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $activeSessions = array_filter($activeSessions, function($session) use ($currentSessionId) {
                    return $session['session_token'] !== $currentSessionId;
                });

                array_unshift($activeSessions, $currentSessionData);

                foreach ($activeSessions as $session) {
                    $deviceIcon = strpos($session['user_agent'], 'Mobile') !== false ? 'ph-device-mobile' : 'ph-desktop';
                    $deviceName = substr($session['user_agent'], strpos($session['user_agent'], '(') + 1, strpos($session['user_agent'], ';') - strpos($session['user_agent'], '(') - 1);
                    $isCurrentSession = $currentSessionData['session_token'] === $session['session_token'];
                    $ipAddress = $session['ip_address'];
                    $createdAt = date('d-m-Y', strtotime($session['created_at']));
                ?>
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2 align-items-center">
                            <i class="ph <?= $deviceIcon ?> text-primary" style="font-size: 32px!important;"></i>
                            <div class="d-flex flex-column">
                                <div class="d-flex gap-1 align-items-center">
                                    <span><?= $deviceName ?></span>
                                    <?= $isCurrentSession ? '<span class="badge text-bg-success">Current</span>' : '' ?>
                                </div>
                                <span class="opacity-75 small"><?= $ipAddress ?></span>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="opacity-75 small">Created on</span>
                            <span><?= $createdAt ?></span>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editSettingsModal">Back</button>
                <button class="btn btn-danger" type="button" id="logout-all-sessions-button" onclick="logoutAllSessions()">Log out of all other sessions</button>
            </div>
        </div>
    </div>
</div>

<?php if (!$hasTotpSecret) { ?>
    <div class="modal fade" id="twoFactorModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="twoFactorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered align-items-end align-items-sm-start">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="twoFactorModalLabel">Two-factor authentication</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div id="two-factor-password-container">
                            <div class="alert alert-danger" id="check-password-button-error" style="opacity: 0; height: 0; margin: 0; padding: 0; white-space: nowrap; overflow: hidden;"></div>

                            <div class="mb-3">
                                <label class="form-label" for="check-password">Password</label>
                                <input class="form-control" type="password" id="check-password" autocomplete="current-password" placeholder="Enter password to verify identity">
                            </div>
                        </div>

                        <div class="d-none" id="setup-two-factor-container">
                            <div class="alert alert-danger" id="setup-two-factor-button-error" style="opacity: 0; height: 0; margin: 0; padding: 0; white-space: nowrap; overflow: hidden;"></div>

                            <p class="mb-3">Install an authenticator app on your phone (e.g. Google Authenticator or Authy) and scan the QR code below to link the app to your account. Not possible to scan? <a class="text-link" id="manual-key-link" onclick="toggleQRCode()" style="cursor:pointer;">Enter key manually</a>.</p>

                            <div class="mb-3 d-flex flex-column justify-content-center align-items-center">
                                <img src="" class="border" id="totp-qr-code" alt="QR Code" style="width: 100%; max-width: 300px;">
                                <p class="h5 d-none" id="totp-secret"></p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="totp-code">Enter code from authenticator app</label>
                                <input class="form-control" type="text" id="totp-code" autocomplete="off" maxlength="6" inputmode="numeric" placeholder="6-digit verification code">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-bs-target="#editSettingsModal">Back</button>
                        <button class="btn btn-primary" type="submit" id="check-password-button" onclick="checkPassword()">Continue</button>
                        <button class="btn btn-primary d-none" type="submit" id="setup-two-factor-button" onclick="setupTwoFactor()">Finish setup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>

<script src="/assets/js/settings.js"></script>

<script>
    const dark = document.getElementById('dark');
    const light = document.getElementById('light');
    const currentTheme = localStorage.getItem('theme') || 'dark';
    document.body.setAttribute('data-bs-theme', currentTheme);

    if (currentTheme === 'dark') {
        dark.checked = true;
    } else {
        light.checked = true;
    }

    dark.addEventListener('change', () => {
        document.querySelectorAll('*').forEach(function(element) {
            element.style.transition = 'none';
        });

        document.body.setAttribute('data-bs-theme', 'dark');
        localStorage.setItem('theme', 'dark');

        setTimeout(() => {
            document.querySelectorAll('*').forEach(function(element) {
                element.style.transition = '';
            });
        }, 100);
    });

    light.addEventListener('change', () => {
        document.querySelectorAll('*').forEach(function(element) {
            element.style.transition = 'none';
        });

        document.body.setAttribute('data-bs-theme', 'light');
        localStorage.setItem('theme', 'light');

        setTimeout(() => {
            document.querySelectorAll('*').forEach(function(element) {
                element.style.transition = '';
            });
        }, 100);
    });
</script>