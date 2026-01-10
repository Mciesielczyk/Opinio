<?php
function checkLogin() {
    // Sprawdzamy, czy sesja jest wystartowana
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Sprawdzamy, czy w sesji zapisaliśmy email/username
    if (!isset($_SESSION['username'])) {
        // Jeśli nie ma sesji, wyrzucamy na login
        header("Location: /login");
        exit();
    }

    // Zwracamy email zalogowanego użytkownika
    return $_SESSION['username'];
}