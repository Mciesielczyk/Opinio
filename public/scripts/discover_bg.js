document.addEventListener("DOMContentLoaded", function() {
    const bgContainer = document.getElementById('userBackground');
    
    if (bgContainer) {
        // Pobieramy nazwę pliku z atrybutu data-background
        const fileName = bgContainer.getAttribute('data-background');
        
        // Budujemy ścieżkę. Jeśli wolisz bez /public, usuń go stąd.
        const path = "/public/uploads/backgrounds/" + fileName;
        
        // Ustawiamy tło
        bgContainer.style.backgroundImage = "url('" + path + "')";
    }
});