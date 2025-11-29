const loginForm = document.querySelector('#login-form');

loginForm.addEventListener('submit', function(e) {
    e.preventDefault(); // zatrzymujemy wysłanie formularza
    document.body.classList.add('animate-background'); // dodajemy animację tła

    // po zakończeniu animacji, przechodzimy dalej
    const pseudo = document.createElement('div');
    pseudo.style.animation = 'none'; // potrzebne żeby złapać event na pseudo?
    // najlepiej użyć timeout zgodny z czasem animacji:
    setTimeout(() => {
        loginForm.submit();
    }, 3000); // 3s = czas animacji
});


const password = document.getElementById("password");
const toggle = document.getElementById("togglePassword");

toggle.addEventListener("click", () => {
    const isPassword = password.type === "password";
    password.type = isPassword ? "text" : "password";

    toggle.textContent = isPassword ? "🙈" : "👁️";
});
