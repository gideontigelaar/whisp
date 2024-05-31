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

                        <button class="btn btn-primary mb-3 w-100" type="button" id="login-button" name="login">Log in</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>