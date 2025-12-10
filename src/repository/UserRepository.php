<?php

require_once 'Repository.php';

class UserRepository extends Repository
{

    private static ?UserRepository $instance = null;

    // 2. Prywatny konstruktor - blokuje użycie "new UserRepository()" z zewnątrz
    private function __construct()
    {
        // Wywołujemy konstruktor rodzica (Repository), żeby nawiązać połączenie z bazą
        // WAŻNE: Klasa Repository NIE powinna być Singletonem, jeśli dziedziczysz w ten sposób.
        // Powinna być zwykłą klasą ustawiającą $this->database.
        parent::__construct(); 
    }

    // 3. Metoda dostępowa (Singleton)
    public static function getInstance(): UserRepository
    {
        if (self::$instance === null) {
            self::$instance = new UserRepository();
        }
        return self::$instance;
    }
    
    public function getUser(): ?array
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM users 
        ');
        $stmt->execute();
        $users = $stmt->fetch(PDO::FETCH_ASSOC);
        return $users;
    }


       public function createUser(string $name, string $surname, string $email,string $hashPassword): void
    {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO public.users (name,surname,email, "password") 
            VALUES (:name, :surname, :email, :password)
        ');

        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashPassword);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':surname', $surname);

        $stmt->execute();

     
    }

    public function getUserByEmail(string $email): ?array
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM users WHERE email = :email
        ');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ? $user : null;
    }
} 