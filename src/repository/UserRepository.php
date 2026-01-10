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


public function createUser(string $name, string $surname, string $email, string $hashPassword): void
{
    // Domyślne nazwy plików
    $defaultAvatar = 'avatar.jpg'; 
    $defaultBackground = 'pl.png';

    $stmt = $this->database->connect()->prepare('
        INSERT INTO public.users (name, surname, email, "password", profile_picture, background_picture) 
        VALUES (:name, :surname, :email, :password, :avatar, :background)
    ');

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':surname', $surname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashPassword);
    $stmt->bindParam(':avatar', $defaultAvatar);
    $stmt->bindParam(':background', $defaultBackground);

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
        return $stmt->fetch(PDO::FETCH_ASSOC); // zwróci tablicę wyników
    }


public function upsertUserScore(
    int $userId,
    float $scoreLewaPrawa,
    float $scoreWladzaWolnosc,
    float $scorePostepKonserwa,
    float $scoreGlobalizmNacjonalizm
) {
    // 1. Przygotowujemy aktualną datę i godzinę w formacie bazy danych
date_default_timezone_set('Europe/Warsaw');
    $currentTime = date('Y-m-d H:i:s');
    // 2. Zamieniamy NOW() na placeholder :now w dwóch miejscach (INSERT i UPDATE)
    $stmt = $this->database->connect()->prepare("
        INSERT INTO user_scores (
            user_id,
            score_lewa_prawa,
            score_wladza_wolnosc,
            score_postep_konserwa,
            score_globalizm_nacjonalizm,
            calculated_at
        )
        VALUES (:user_id, :slp, :sw, :spk, :sg, :now)
        ON CONFLICT (user_id) DO UPDATE SET
            score_lewa_prawa = EXCLUDED.score_lewa_prawa,
            score_wladza_wolnosc = EXCLUDED.score_wladza_wolnosc,
            score_postep_konserwa = EXCLUDED.score_postep_konserwa,
            score_globalizm_nacjonalizm = EXCLUDED.score_globalizm_nacjonalizm,
            calculated_at = :now
    ");

    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    
    // Bindowanie wyników jako string (bezpieczne dla floatów z kropką)
    $stmt->bindValue(':slp', $scoreLewaPrawa, PDO::PARAM_STR);
    $stmt->bindValue(':sw', $scoreWladzaWolnosc, PDO::PARAM_STR);
    $stmt->bindValue(':spk', $scorePostepKonserwa, PDO::PARAM_STR);
    $stmt->bindValue(':sg', $scoreGlobalizmNacjonalizm, PDO::PARAM_STR);
    
    // 3. Bindowanie czasu
    $stmt->bindValue(':now', $currentTime, PDO::PARAM_STR);

    return $stmt->execute();
}

public function deleteUser(int $id): void {
    $stmt = $this->database->connect()->prepare('DELETE FROM users WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}

public function getAllUsers(): array {
    $stmt = $this->database->connect()->prepare('SELECT id, name, surname, email, role, created_at FROM users');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function updateUserRole(int $userId, string $role): void {
    $stmt = $this->database->connect()->prepare('
        UPDATE users SET role = :role WHERE id = :id
    ');
    $stmt->execute([
        'role' => $role,
        'id' => $userId
    ]);
}

public function updateProfilePicture(string $email, string $fileName) {
    $stmt = $this->database->connect()->prepare('
        UPDATE users SET profile_picture = :fileName WHERE email = :email
    ');
    $stmt->bindParam(':fileName', $fileName);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
}

public function updateBackgroundPicture(string $email, string $fileName) {
    $stmt = $this->database->connect()->prepare('
        UPDATE users SET background_picture = :fileName WHERE email = :email
    ');
    $stmt->bindParam(':fileName', $fileName);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
}



public function updateUserDetails(string $email, string $name, string $surname, string $description) {
    $stmt = $this->database->connect()->prepare('
        UPDATE users SET 
            name = :name, 
            surname = :surname, 
            description = :description 
        WHERE email = :email
    ');
    
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    
    $stmt->execute();
}


public function getUserById(int $id): ?array {
    $stmt = $this->database->connect()->prepare('
        SELECT * FROM users WHERE id = :id LIMIT 1;
    ');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user ? $user : null;

}
}