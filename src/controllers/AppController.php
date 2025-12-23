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
    {//metoda renderujaca widok
        $templatePath = 'public/views/'. $template.'.html';
        $templatePath404 = 'public/views/404.html';
        $output = "";
                 
        if(file_exists($templatePath)){//sprawdzamy czy plik istnieje
            extract($variables); //tworzy zmienne z kluczy tablicy
            ob_start();//rozpoczynamy buforowanie outputu
         //Pozwala na przechwycenie zawartości pliku zamiast od razu wyświetlać ją w przeglądarce
          
            include $templatePath;//wczytujemy plik szablonu
            $output = ob_get_clean();//pobieramy zawartosc bufora i czyscimy bufor
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