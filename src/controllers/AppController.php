<?php


class AppController
{ //kalsa bazowa dla kontrolerow

    protected function isGet(): bool
    {
        return $_SERVER["REQUEST_METHOD"] === 'GET';
    }

    protected function isPost(): bool
    {
        return $_SERVER["REQUEST_METHOD"] === 'POST';
    }

    public function render(?string $template = null, array $variables = [])
    {
        $templatePath = 'public/views/' . $template . '.php';
        if (!file_exists($templatePath)) {
            $templatePath = 'public/views/' . $template . '.html';
        }

        $templatePath404 = 'public/views/404.html';

        if (file_exists($templatePath)) {
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


    protected function isLoggedIn(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']);
    }

    protected function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            $this->terminateWithCode(401, "Musisz się zalogować, aby zobaczyć tę stronę.");
        }
    }

    protected function requireAdmin()
    {
        $this->requireLogin(); 

        if ($_SESSION['user_role'] !== 'admin') {
            $this->terminateWithCode(403, "Dostęp zabroniony. Ta strona wymaga uprawnień administratora.");
        }
    }

    protected function terminateWithCode(int $code, string $message)
    {
        http_response_code($code);
        $this->render('error', [
            'code' => $code,
            'message' => $message
        ]);
        exit;
    }
}
