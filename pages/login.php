<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/api/validate-session.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A place for developers to learn and share.">

    <title>Login / Whisp</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

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
        <div class="row justify-content-center align-items-center" style="height: 100dvh;">
            <div class="card col-11 col-md-6 col-lg-5 col-xl-4 col-xxl-3 rounded-4">
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

                        <div class="row mb-3">
                            <div class="col-6">
                                <label for="register-password" class="form-label">Password</label>
                                <input type="password" id="register-password" class="form-control" autocomplete="new-password">
                            </div>

                            <div class="col-6">
                                <label for="register-password-confirm" class="form-label">Confirm password</label>
                                <input type="password" id="register-password-confirm" class="form-control" autocomplete="new-password">
                            </div>
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
</body>
</html>