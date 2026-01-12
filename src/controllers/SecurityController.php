<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class SecurityController extends AppController
{
    private $message = [];
    private $userRepository;


    public function __construct()
    {
        $this->userRepository = UserRepository::getInstance();
    }

    public function login()
    {
        if (!$this->isPost()) {
            return $this->render('login');
        }


        $email = $_POST["email"] ?? '';
        $password = $_POST["password"] ?? '';



        if (empty($email) || empty($password)) {
            return $this->render('login', ['message' => 'Fill all fields']);
        }


        $userRow =  $this->userRepository->getUserByEmail($email);

        //var_dump($password, $userRow['password'], password_verify($password, $userRow['password']));

        if (!$userRow) { //nie znaleziono uzytkownika
            return $this->render('login', ['message' => 'Email lub hasło niepoprawne']);
        }


        if (!password_verify($password, $userRow['password'])) { //sprawdzamy haslo
            return $this->render('login', ['message' => 'Email lub hasło niepoprawne']);
        }


        
        session_set_cookie_params([
            'lifetime' => 0,          // Ciasteczko wygaśnie po zamknięciu przeglądarki
            'path' => '/',            // Dostępne w całej domenie
            'domain' => '',           // Bieżąca domena
            'secure' => true,         // FLAG: SECURE (wysyłaj tylko przez HTTPS)
            'httponly' => true,       // FLAG: HTTPONLY (brak dostępu dla JavaScript)
            'samesite' => 'Lax'       // Ochrona przed atakami CSRF
        ]);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_regenerate_id(true);
        $_SESSION['user_id'] = $userRow['id']; // ID użytkownika
        $_SESSION['username'] = $userRow['email']; //  e-mail
        $_SESSION['user_role'] = $userRow['role']; // rola użytkownika
        $url = "https://$_SERVER[HTTP_HOST]"; //przekierowanie na dashboard
        header("Location: {$url}/discover");
    }



    public function register()
    {

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $csrf_token = $_SESSION['csrf_token'];

        if ($this->isGet()) {
            return $this->render("register");
        }


        $email = $_POST["email"] ?? '';
        $password1 = $_POST["password1"] ?? '';
        $password2 = $_POST["password2"] ?? '';
        $name = $_POST["name"] ?? '';
        $surname = $_POST["surname"] ?? '';

        if (strlen($name) > 50 || strlen($surname) > 50) {
            return $this->render('register', ['message' => 'Imię i nazwisko mogą mieć max 50 znaków']);
        }
        if (strlen($email) > 255) {
            return $this->render('register', ['message' => 'Email jest zbyt długi']);
        }

        if (strlen($password1) > 72) {
            return $this->render('register', ['message' => 'Hasło nie może przekraczać 72 znaków']);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->render('register', ['message' => 'Invalid email format']);
        }
        if (empty($email) || empty($password1) || empty($name) || empty($surname)) {
            return $this->render('register', ['message' => 'Fill all fields']);
        }
        $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
        if (!preg_match($passwordRegex, $password1)) {
            return $this->render('register', [
                'message' => 'Hasło musi mieć min. 8 znaków, zawierać dużą i małą literę, cyfrę oraz znak specjalny (@$!%*?&)'
            ]);
        }
        if ($password1 !== $password2) {
            return $this->render('register', ['message' => 'Passwords do not match']);
        }


        $hashPassword = password_hash($password1, PASSWORD_BCRYPT);

        if ($this->userRepository->getUserByEmail($email)) {
            return $this->render('register', ['message' => 'Uzytkownik o podanym emailu może juz istniec']);
        }
        $this->userRepository->createUser(
            $name,
            $surname,
            $email,
            $hashPassword
        );


        return $this->render("login", ["message" => "Zarejestrowano uytkownika " . $email]);
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        header("Location: /login");
        exit;
    }
}
