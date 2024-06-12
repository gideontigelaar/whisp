<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/queries/validate-session.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A place for developers to learn and share.">

    <title>Whisp</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/login.css">

    <script src="/assets/js/index.js"></script>
    <script src="/assets/js/login.js"></script>
</head>
<body class="bg-body-tertiary" data-bs-theme="dark">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center align-items-center" style="height: 100dvh;">
            <div class="col-11 col-md-6 col-lg-5 col-xl-4 col-xxl-3 p-4 card rounded-4">
                <div class="p-2">
                    <div class="mb-3">
                        <img class="mb-3" src="/assets/images/logos/whisp-logo-horizontal.svg" width="250" alt="Whisp logo">
                        <p class="h1 d-none d-md-block">Hey, hello 👋</p>
                        <h1 class="h4">A place for developers to learn and share.</h1>
                    </div>

                    <form method="post" id="login-form">
                        <div class="alert alert-danger" id="login-button-error" style="opacity: 0; height: 0; margin: 0; padding: 0; white-space: nowrap; overflow: hidden;"></div>

                        <div class="mb-3">
                            <label class="form-label" for="login-username">Username</label>
                            <input class="form-control" type="text" id="login-username" autocomplete="username">
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="login-password">Password</label>
                            <input class="form-control" type="password" id="login-password" autocomplete="current-password">
                        </div>

                        <button class="btn btn-primary mb-3 w-100" type="submit" id="login-button" onclick="loginUser()">Log in</button>
                        <div class="text-center">
                            <span>Don't have an account yet? <u role="button" onclick="toggleLoginForm()">Create one.</u></span>
                        </div>
                    </form>

                    <form class="d-none" method="post" id="register-form">
                        <div class="alert alert-danger" id="register-button-error" style="opacity: 0; height: 0; margin: 0; padding: 0; white-space: nowrap; overflow: hidden;"></div>

                        <div class="mb-3">
                            <label class="form-label" for="register-username">Username</label>
                            <input class="form-control" type="text" id="register-username" autocomplete="username">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="register-email">Email</label>
                            <input class="form-control" type="email" id="register-email" autocomplete="email">
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="register-password">Password</label>
                                    <input class="form-control" type="password" id="register-password" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="register-password-confirm">Confirm password</label>
                                    <input class="form-control" type="password" id="register-password-confirm" autocomplete="new-password">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="register-invite-code">Invite code</label>
                            <input class="form-control" type="text" id="register-invite-code" autocomplete="off">
                        </div>

                        <button class="btn btn-primary mb-3 w-100" type="submit" id="register-button" onclick="registerUser()">Create account</button>
                        <div class="text-center">
                            <span>Already have an account? <u role="button" onclick="toggleLoginForm()">Log in.</u>
                        </span>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>