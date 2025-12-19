const form = document.querySelector('#register-form');
const pass = document.getElementById('password');
const confirm = document.getElementById('confirm_password');
const msg = document.getElementById('pass_msg');

function validatePassword() {
    if (pass.value.length < 8) {
        msg.textContent = "Hasło musi mieć min. 8 znaków";
        return false;
    }
    if (pass.value !== confirm.value) {
        msg.textContent = "Hasła nie są takie same";
        return false;
    }
    msg.textContent = "";
    return true;
}

pass.addEventListener('input', validatePassword);
confirm.addEventListener('input', validatePassword);

form.addEventListener('submit', function(e) {
    if (!validatePassword()) {
        e.preventDefault(); // blokuje wysyłkę formularza
    }
});