const loginForm = document.querySelector('#login-form'); // tylko login
const btn = loginForm.querySelector('button');

loginForm.addEventListener('submit', function(e) {
    e.preventDefault(); // zatrzymuje natychmiastowe wysłanie formularza
    btn.classList.add('loading'); // dodaje klasę animacji

    // po zakończeniu animacji wysyłamy formularz
    btn.addEventListener('animationend', () => {
        loginForm.submit(); // wysyła formularz po zakończeniu animacji
    }, { once: true });
});
