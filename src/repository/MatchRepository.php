<?php

require_once 'Repository.php';

class MatchRepository extends Repository
{
public function getRandomMatch(int $my_id): ?array
{
    $stmt = $this->database->connect()->prepare('
        SELECT u.id, u.name, u.surname, u.profile_picture, u.background_picture, u.description, u.location,
               us.score_lewa_prawa, us.score_wladza_wolnosc, 
               us.score_postep_konserwa, us.score_globalizm_nacjonalizm
        FROM users u
        LEFT JOIN user_scores us ON us.user_id = u.id
        LEFT JOIN interactions i ON i.target_id = u.id AND i.user_id = :my_id
        LEFT JOIN friends f ON (f.user_id_1 = u.id AND f.user_id_2 = :my_id) 
                            OR (f.user_id_2 = u.id AND f.user_id_1 = :my_id)
        WHERE u.id != :my_id 
        AND f.id IS NULL
        /* Pokazujemy tylko tych, co nie mają interakcji LUB mają "maybe" */
        AND (i.id IS NULL OR i.action = \'maybe\')
        /* Sortowanie: najpierw te bez interakcji (NULL), potem "maybe", a wewnątrz grup losowo */
        ORDER BY 
            (CASE WHEN i.id IS NULL THEN 0 ELSE 1 END) ASC, 
            RANDOM() 
        LIMIT 1
    ');
    
    $stmt->bindParam(':my_id', $my_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}
  
    
    public function addInteraction(int $userId, int $targetId, string $action) {
    $stmt = $this->database->connect()->prepare('
        INSERT INTO interactions (user_id, target_id, action)
        VALUES (:u, :t, :a)
        ON CONFLICT (user_id, target_id) 
        DO UPDATE SET 
            action = EXCLUDED.action,
            created_at = CURRENT_TIMESTAMP
    ');
    
    $stmt->execute([
        'u' => $userId,
        't' => $targetId,
        'a' => $action
    ]);
}

    public function addFriend(int $user_id_1, int $user_id_2): void
    {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO friends (user_id_1, user_id_2) 
            VALUES (:user_id_1, :user_id_2)
        ');
        $stmt->bindParam(':user_id_1', $user_id_1, PDO::PARAM_INT);
        $stmt->bindParam(':user_id_2', $user_id_2, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function checkMatch(int $userId, int $targetId): bool {
        $stmt = $this->database->connect()->prepare('
            SELECT 1 FROM interactions 
            WHERE user_id = ? AND target_id = ? AND action = \'like\'
        ');
        $stmt->execute([$targetId, $userId]); // Sprawdzamy czy TARGET polubił USERA
        return (bool)$stmt->fetch();
    }

} 