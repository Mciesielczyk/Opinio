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
            SELECT * FROM users WHERE email = :email LIMIT 1;
        ');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ? $user : null;
    }

    public function getUserScores(int $userId) {
        $stmt = $this->database->connect()->prepare("
            SELECT score_lewa_prawa,
                score_wladza_wolnosc,
                score_postep_konserwa,
                score_globalizm_nacjonalizm,
                calculated_at
            FROM user_scores
            WHERE user_id = :user_id
            ORDER BY calculated_at DESC
        ");
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // zwróci tablicę wyników
    }


    public function upsertUserScore(
    int $userId,
    int $scoreLewaPrawa,
    int $scoreWladzaWolnosc,
    int $scorePostepKonserwa,
    int $scoreGlobalizmNacjonalizm
    ) {
    $stmt = $this->database->connect()->prepare("
        INSERT INTO user_scores (
            user_id,
            score_lewa_prawa,
            score_wladza_wolnosc,
            score_postep_konserwa,
            score_globalizm_nacjonalizm,
            calculated_at
        )
        VALUES (:user_id, :slp, :sw, :spk, :sg, NOW())
        ON CONFLICT (user_id) DO UPDATE SET
            score_lewa_prawa = EXCLUDED.score_lewa_prawa,
            score_wladza_wolnosc = EXCLUDED.score_wladza_wolnosc,
            score_postep_konserwa = EXCLUDED.score_postep_konserwa,
            score_globalizm_nacjonalizm = EXCLUDED.score_globalizm_nacjonalizm,
            calculated_at = NOW()
    ");

    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':slp', $scoreLewaPrawa, PDO::PARAM_INT);
    $stmt->bindValue(':sw', $scoreWladzaWolnosc, PDO::PARAM_INT);
    $stmt->bindValue(':spk', $scorePostepKonserwa, PDO::PARAM_INT);
    $stmt->bindValue(':sg', $scoreGlobalizmNacjonalizm, PDO::PARAM_INT);

    return $stmt->execute();
}


} 