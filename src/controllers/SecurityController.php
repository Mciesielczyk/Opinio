<?php

require_once 'AppController.php';


class SecurityController extends AppController
{
    private $message = []; //tablica wiadomosci do wyswietlenia na stronie

    // ======= LOKALNA "BAZA" UŻYTKOWNIKÓW =======
    private static array $users = [ //tablica asocjacyjna przechowujaca uzytkownikow
        [
            'email' => 'anna@example.com',
            'password' => '$2y$10$0JrlJSBcUFejPW8T9Hxp2eX7LoBS3js0w421NfsZ16J48.t9xflG2', // test123
            'first_name' => 'Anna'
        ],
        [
            'email' => 'bartek@example.com',
            'password' => '$2y$10$fK9rLobZK2C6rJq6B/9I6u6Udaez9CaRu7eC/0zT3pGq5piVDsElW', // haslo456
            'first_name' => 'Bartek'
        ],
        [
            'email' => 'celina@example.com',
        'password' => '$2y$10$Yh1LU37ObashfPyYfkaYiezvWzYX7dKF8SM251kPMhTL4cJ8Eb5N', // qwerty
            'first_name' => 'Celina'
        ],
    ];

    public function __construct() 
    {
        //parent::__construct();//wywolanie konstruktora klasy bazowej
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
        $userRow = null; 
        foreach (self::$users as $u) { //przeszukujemy tablice uzytkownikow
            if (strcasecmp($u['email'], $email) === 0) {
                $userRow = $u;//znalezlismy uzytkownika
                break;
            }
        }
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
        // setcookie("username", $userRow['email'], time() + 3600, '/');

        $url = "http://$_SERVER[HTTP_HOST]";//przekierowanie na dashboard
        header("Location: {$url}/dashboard");
    }



    public function register() //rejestracja uzytkownika
    {
        if (!$this->isPost()) { 
            return $this->render('register');//wyswietlamy formularz rejestracji
        }


            $email = trim($_POST['email'] ?? '');
            $password1 = $_POST['password1'] ?? '';
            $password2 = $_POST['password2'] ?? '';
            $firstName = $_POST['firstname'] ?? '';
            $lastName = $_POST['lastname'] ?? '';

            if (empty($email) || empty($password1) || empty($password2) || empty($firstName) || empty($lastName)) {
                return $this->render('register', ['message' => 'Wypełnij wszystkie pola']);
            }

            if ($password1 !== $password2) {
                return $this->render('register', ['message' => 'Hasła nie są takie same']);
            }

            // sprawdzamy czy email jest zajęty
            foreach (self::$users as $u) {
                if (strcasecmp($u['email'], $email) === 0) {
                    return $this->render('register', ['message' => 'Email jest zajęty']);
                }
            }

            $hashedPassword = password_hash($password1, PASSWORD_BCRYPT);

            self::$users[] = [
                'email' => $email,
                'password' => $hashedPassword,
                'first_name' => $firstName,
                'last_name' => $lastName
            ];

            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/login");
    }
}
