const form = document.querySelector("form");
const emailInput = form.querySelector('input[name="email"]');
const confirmedPasswordInput = form.querySelector('input[name="password2"]');
const PasswordInput = form.querySelector('input[name="password1"]');
const nameInput = form.querySelector('input[name="name"]');
const surnameInput = form.querySelector('input[name="surname"]');


document.querySelector("form");

function isEmail(email) {
    return /\S+@\S+\.\S+/.test(email);
}

function arePasswordsSame(password, confirmedPassword) {
    return password === confirmedPassword;
}


function isStrongPassword(password) {
    return password.length >= 6;
}

function isLongerThan2(text){
    return text.length > 2;
}
function hasValidNameChars(text){
    return /^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ' -]+$/.test(text);
}


function markValidation(element, condition) {
    !condition 
        ? element.classList.add('no-valid') 
        : element.classList.remove('no-valid');
}

function validateEmail() {
    setTimeout(function () {
        markValidation(emailInput, isEmail(emailInput.value));
    }, 1000);
}

function validatePassword() {
    setTimeout(function () {
        const condition = arePasswordsSame(
            confirmedPasswordInput.previousElementSibling.value,
            confirmedPasswordInput.value
         ) 
        markValidation(confirmedPasswordInput, condition);
    }, 1000);

    setTimeout(function () {
        markValidation(PasswordInput, isStrongPassword(PasswordInput.value));
    }, 1000);
}

function validateName(){
    setTimeout(function(){
        markValidation(nameInput, isLongerThan2(nameInput.value) && hasValidNameChars(nameInput.value));
    }, 1000);     
}
function validateSurname(){
    setTimeout(function(){
        markValidation(surnameInput, isLongerThan2(surnameInput.value) && hasValidNameChars(surnameInput.value));
    }, 1000);
}

emailInput.addEventListener('keyup', validateEmail);
confirmedPasswordInput.addEventListener('keyup', validatePassword);

nameInput.addEventListener('keyup', validateName);
surnameInput.addEventListener('keyup', validateSurname);