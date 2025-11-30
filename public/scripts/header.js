function setupSettingsDropdown() {
    const settingsBtn = document.getElementById('settingsBtn');
    const settingsDropdown = document.getElementById('settingsDropdown');

    if (!settingsBtn || !settingsDropdown) return; // zabezpieczenie

    settingsBtn.addEventListener('click', (e) => {
        e.stopPropagation(); // zapobiega natychmiastowemu zamknięciu menu
        settingsDropdown.style.display = settingsDropdown.style.display === 'block' ? 'none' : 'block';
    });

    window.addEventListener('click', (e) => {
        if (!settingsBtn.contains(e.target) && !settingsDropdown.contains(e.target)) {
            settingsDropdown.style.display = 'none';
        }
    });
}

// Wywołanie funkcji po załadowaniu dokumentu
document.addEventListener('DOMContentLoaded', setupSettingsDropdown);
