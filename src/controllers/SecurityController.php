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
         $this->userRepository = new UserRepository();
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
            return $this->render('login', ['message' => 'User not found']);
        }
//var_dump(strlen($password), strlen($userRow['password']));
//var_dump($userRow['password']);

        if (!password_verify($password, $userRow['password'])) {//sprawdzamy haslo
            return $this->render('login', ['message' => 'Wrong password']);
        }

        // TODO możemy przechowywać sesje użytkowika lub token
         setcookie("username", $userRow['email'], time() + 3600, '/');

        $url = "http://$_SERVER[HTTP_HOST]";//przekierowanie na dashboard
        header("Location: {$url}/dashboard");
    }



    public function register() {
        // TODO pobranie z formularza email i hasła
        // TODO insert do bazy danych
        // TODO zwrocenie informajci o pomyslnym zarejstrowaniu

        if ($this->isGet()) {
            return $this->render("register");
        }

        $email = $_POST["email"] ?? '';
        $password1 = $_POST["password1"] ?? '';
        $password2 = $_POST["password2"] ?? '';
        $name = $_POST["name"] ?? '';
        $surname = $_POST["surname"] ?? '';
      

        if (empty($email) || empty($password1) || empty($name) || empty($surname)) {
            return $this->render('register', ['message' => 'Fill all fields']);
        }
   // die("Funkcja bbbb() działa!");

        if ($password1 !== $password2) {
            return $this->render('register', ['message' => 'Passwords do not match']);
        }


        $hashPassword = password_hash($password1, PASSWORD_BCRYPT);
      
        $this->userRepository->createUser(
            $name,
            $surname,
            $email,
            $hashPassword);
        // TODO insert to database user

        return $this->render("login", ["message" => "Zarejestrowano uytkownika ".$email]);
    }
}
