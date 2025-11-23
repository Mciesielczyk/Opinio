const form = document.querySelector('#register-form');

form.addEventListener('submit', function(e) {
    const email = form.querySelector('input[name="email"]').value;
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailPattern.test(email)) {
        e.preventDefault(); // zatrzymanie wysłania formularza
        const msg = document.querySelector('.message');
        msg.textContent = 'Podaj poprawny email!';
        msg.style.color = '#ff4444';
        msg.style.fontWeight = '700';
        return;
    }
});
