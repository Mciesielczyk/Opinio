<?php
function checkLogin() {
    if (!isset($_COOKIE['username'])) {
        header("Location: /login");
        exit();
    }

    // tutaj możesz zwrócić lub echo dane użytkownika
    return $_COOKIE['username'];
}