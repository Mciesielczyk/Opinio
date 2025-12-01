document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('themeBtn');
    if (!btn) return; // zabezpieczenie

    btn.addEventListener('click', () => {
        const body = document.body;

        if (body.classList.contains('dark-mode')) {
            body.classList.remove('dark-mode');
            body.classList.add('red-mode');
        } else if (body.classList.contains('red-mode')) {
            body.classList.remove('red-mode');
        } else {
            body.classList.add('dark-mode');
        }
    });
});
