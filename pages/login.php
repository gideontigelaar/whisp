<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Whisp</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/login.css">

    <script src="/assets/js/index.js"></script>
    <script src="/assets/js/login-page.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row justify-content-center d-flex align-items-center" style="height: 100dvh;">
            <div class="col-12 col-md-6 col-xl-5 col-xxl-3 p-4 card-dark">
                <div class="p-2">
                    <div class="mb-3">
                        <img class="mb-3" src="/assets/images/logos/whisp-logo-horizontal.svg" width="250px" alt="Whisp logo">
                        <h1 class="d-none d-md-block">Hey, hello 👋</h1>
                        <h4>A place for developers to learn and share.</h4>
                    </div>

                    <form method="post" id="login-form">
                        <div class="mb-3">
                            <label class="form-label" for="login-username">Username</label>
                            <input class="form-control" type="text" id="login-username" name="login-username" autocomplete="username">
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="login-password">Password</label>
                            <input class="form-control" type="password" id="login-password" name="login-password" autocomplete="current-password">
                        </div>

                        <button class="btn btn-primary mb-3 w-100" type="button" id="login-button" name="login" onclick="loginUser()">Log in</button>
                        <p class="text-center">Don't have an account yet? <u role="button" onclick="toggleLoginForm()">Register.</u></p>
                    </form>

                    <form class="d-none" method="post" id="register-form">
                        <div class="mb-3">
                            <label class="form-label" for="register-username">Username</label>
                            <input class="form-control" type="text" id="register-username" name="register-username" autocomplete="username">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="register-email">Email</label>
                            <input class="form-control" type="email" id="register-email" name="register-email" autocomplete="email">
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="register-password">Password</label>
                                    <input class="form-control" type="password" id="register-password" name="register-password" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="register-password-confirm">Confirm password</label>
                                    <input class="form-control" type="password" id="register-password-confirm" name="register-password-confirm" autocomplete="new-password">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="register-invite-code">Invite code</label>
                            <input class="form-control" type="text" id="register-invite-code" name="register-invite-code" autocomplete="off">
                        </div>

                        <button class="btn btn-primary mb-3 w-100" type="button" id="register-button" name="register" onclick="registerUser()">Register</button>
                        <p class="text-center">Already have an account? <u role="button" onclick="toggleLoginForm()">Log in.</u></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>