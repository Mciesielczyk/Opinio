<?php


class AppController { //kalsa bazowa dla kontrolerow

    protected function isGet(): bool
    {
        return $_SERVER["REQUEST_METHOD"] === 'GET';
    }

    protected function isPost(): bool
    {
        return $_SERVER["REQUEST_METHOD"] === 'POST';
    }

    protected function render(string $template = null, array $variables = [])
{
    // Najpierw sprawdzamy wersję .php, potem .html
    $templatePath = 'public/views/'. $template . '.php';
    if (!file_exists($templatePath)) {
        $templatePath = 'public/views/'. $template . '.html';
    }

    $templatePath404 = 'public/views/404.html';
    
    if(file_exists($templatePath)) {
        extract($variables);
        ob_start();
        include $templatePath;
        $output = ob_get_clean();
    } else {
        ob_start();
        include $templatePath404;
        $output = ob_get_clean();
    }
    echo $output;
}

    
    protected function isLoggedIn(): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']);
    }

    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            header("Location: /login");
            exit;
        }
    }

    protected function requireAdmin() {
    $this->requireLogin(); // Najpierw sprawdź czy zalogowany
    
    if ($_SESSION['user_role'] !== 'admin') {
        // Jeśli nie admin, przekieruj na dashboard lub pokaż błąd
        header("Location: /disvover");
    }
}

}