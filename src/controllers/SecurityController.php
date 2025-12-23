<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/UserRepository.php';

class SecurityController extends AppController
{
    private $message = []; //tablica wiadomosci do wyswietlenia na stronie
    private $userRepository;
 
    
    public function __construct() 
    {
        //parent::__construct();//wywolanie konstruktora klasy bazowej
         $this->userRepository = UserRepository::getInstance();
    }

    public function login()
    {
        if (!$this->isPost()) {
            return $this->render('login');
        }
   

//echo password_hash('test123', PASSWORD_BCRYPT);   // dla Anna
//echo password_hash('haslo456', PASSWORD_BCRYPT); // dla Bartek
//echo password_hash('qwerty', PASSWORD_BCRYPT);   // dla Celina
        $email = $_POST["email"] ?? '';
        $password = $_POST["password"] ?? '';

     

        if (empty($email) || empty($password)) { //sprawdzamy czy pola nie sa puste
        return $this->render('login', ['message' => 'Fill all fields']);
        }

       //TODO replace with search from database
        $userRow =  $this->userRepository->getUserByEmail($email);
 
//var_dump($password, $userRow['password'], password_verify($password, $userRow['password']));
//die();

        if (!$userRow) {//nie znaleziono uzytkownika
            return $this->render('login', ['message' => 'Email lub hasło niepoprawne']);
        }
//var_dump(strlen($password), strlen($userRow['password']));
//var_dump($userRow['password']);

        if (!password_verify($password, $userRow['password'])) {//sprawdzamy haslo
            return $this->render('login', ['message' => 'Email lub hasło niepoprawne']);
        }

        // TODO możemy przechowywać sesje użytkowika lub token
         setcookie("username", $userRow['email'], time() + 3600, '/');
         
        session_start(); // uruchom sesję jeśli jeszcze nie uruchomiona
        $_SESSION['user_id'] = $userRow['id']; // ID użytkownika
        $_SESSION['username'] = $userRow['email']; // opcjonalnie e-mail
        $_SESSION['user_role'] = $userRow['role']; // rola użytkownika
        $url = "https://$_SERVER[HTTP_HOST]";//przekierowanie na dashboard
        header("Location: {$url}/discover");
    }



    public function register() {
        // TODO pobranie z formularza email i hasła
        // TODO insert do bazy danych
        // TODO zwrocenie informajci o pomyslnym zarejstrowaniu
        
        

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
      
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->render('register', ['message' => 'Invalid email format']);
        }
        if (empty($email) || empty($password1) || empty($name) || empty($surname)) {
            return $this->render('register', ['message' => 'Fill all fields']);
        }
   // die("Funkcja bbbb() działa!");

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
            $hashPassword);
        // TODO insert to database user

        return $this->render("login", ["message" => "Zarejestrowano uytkownika ".$email]);
    }

    public function logout() {
        // Startujemy sesję jeśli jeszcze nie startowana
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Czyszczenie wszystkich zmiennych sesji
        $_SESSION = [];

        // Niszczymy ciasteczko sesyjne
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Niszczymy sesję na serwerze
        session_destroy();

        // Przekierowanie na stronę logowania
        header("Location: /login");
        exit;
    }

}
