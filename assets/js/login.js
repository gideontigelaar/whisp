function toggleLoginForm() {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');

    [loginForm, registerForm].forEach(form => {
        form.classList.toggle('d-none');
        form.classList.toggle('d-flex');
    });
}

function loginUser() {
    var username = document.getElementById('login-username').value;
    var password = document.getElementById('login-password').value;
    setButtonLoadingState(['login-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../queries/login-user.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                location.reload();
            } else {
                setButtonLoadingState(['login-button'], false, false);
                var response = JSON.parse(xhr.responseText);
                showError(response.error, true, 'login-button');
            }
        }
    }
    xhr.send('username=' + username + '&password=' + password);
}

function registerUser() {
    var username = document.getElementById('register-username').value;
    var email = document.getElementById('register-email').value;
    var password = document.getElementById('register-password').value;
    var passwordConfirm = document.getElementById('register-password-confirm').value;
    var inviteCode = document.getElementById('register-invite-code').value;
    setButtonLoadingState(['register-button'], true, true);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../../queries/register-user.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                location.reload();
            } else {
                setButtonLoadingState(['register-button'], false, false);
                var response = JSON.parse(xhr.responseText);
                showError(response.error, true, 'register-button');
            }
        }
    }
    xhr.send('username=' + username + '&email=' + email + '&password=' + password + '&password_confirm=' + passwordConfirm + '&invite_code=' + inviteCode);
}