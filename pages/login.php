<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/api/validate-session.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A place for developers to learn and share.">

    <title>Login / Whisp</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="/assets/css/styles.css">

    <script src="/assets/js/index.js"></script>
    <script src="/assets/js/login.js"></script>

    <style>
        @media (min-width: 768px) {
            body {
                background-image: url("/assets/images/wave-left.svg"), url("/assets/images/wave-right.svg");
                background-repeat: no-repeat;
                background-position: left bottom, right top;
                background-size: 50dvw;
            }
        }
    </style>
</head>
<body class="bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center py-3" style="min-height: 100dvh;">
            <div class="card col-11 rounded-4 login-card">
                <div class="card-body d-flex flex-column gap-3 p-4">
                    <img src="/assets/images/logos/whisp-logo-horizontal.svg" alt="Whisp logo" width="250">

                    <div>
                        <p class="h1 d-none d-md-block">Hey, hello 👋</p>
                        <h1 class="h4">A place for developers to<br>learn and share.</h1>
                    </div>

                    <form method="post" class="d-flex flex-column m-0" id="login-form">
                        <div class="alert alert-danger" id="login-button-error" style="opacity: 0; height: 0; margin: 0; padding: 0; white-space: nowrap; overflow: hidden;"></div>

                        <div class="mb-3">
                            <label for="login-username" class="form-label">Username</label>
                            <input type="text" id="login-username" class="form-control" autocomplete="username">
                        </div>

                        <div class="mb-3">
                            <label for="login-password" class="form-label">Password</label>
                            <input type="password" id="login-password" class="form-control" autocomplete="current-password">
                        </div>

                        <div class="collapse-field" id="totp-field" inert>
                            <div>
                                <div class="pb-3">
                                    <label for="login-totp-code" class="form-label">2FA Code</label>
                                    <input type="text" id="login-totp-code" class="form-control" autocomplete="one-time-code" maxlength="6" inputmode="numeric">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3" id="login-button" onclick="loginUser()">Log in</button>

                        <div class="text-center">
                            <span>Don't have an account? <u role="button" onclick="toggleLoginForm()">Create one.</u></span>
                        </div>
                    </form>

                    <form method="post" class="d-none flex-column m-0" id="register-form">
                        <div class="alert alert-danger" id="register-button-error" style="opacity: 0; height: 0; margin: 0; padding: 0; white-space: nowrap; overflow: hidden;"></div>

                        <div class="mb-3">
                            <label for="register-username" class="form-label">Username</label>
                            <input type="text" id="register-username" class="form-control" autocomplete="username">
                        </div>

                        <div class="mb-3">
                            <label for="register-email" class="form-label">Email</label>
                            <input type="email" id="register-email" class="form-control" autocomplete="email">
                        </div>

                        <div class="mb-3">
                            <label for="register-password" class="form-label">Password</label>
                            <input type="password" id="register-password" class="form-control" autocomplete="new-password">
                        </div>

                        <div class="mb-3">
                            <label for="register-password-confirm" class="form-label">Confirm password</label>
                            <input type="password" id="register-password-confirm" class="form-control" autocomplete="new-password">
                        </div>

                        <div class="mb-3">
                            <label for="register-invite-code" class="form-label">Invite code</label>
                            <input type="text" id="register-invite-code" class="form-control" autocomplete="off">
                        </div>


                        <button type="submit" class="btn btn-primary w-100 mb-3" id="register-button" onclick="registerUser()">Create account</button>

                        <div class="text-center">
                            <span>Already have an account? <u role="button" onclick="toggleLoginForm()">Log in.</u></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="registerSuccessModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="registerSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered align-items-end align-items-sm-start">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="registerSuccessModalLabel">Account created</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    Your account has been created! You can now log in with your username and password.
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Log in</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>