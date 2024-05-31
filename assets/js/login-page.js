function toggleLoginForm() {
    var loginForm = document.getElementById('login-form');
    var registerForm = document.getElementById('register-form');

    loginForm.classList.toggle('d-none');
    registerForm.classList.toggle('d-none');
}