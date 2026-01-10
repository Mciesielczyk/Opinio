<?php

require_once 'Repository.php';

class MatchRepository extends Repository
{
public function getRandomMatch(int $my_id): ?array
{
    $stmt = $this->database->connect()->prepare('
        SELECT v.* FROM v_discover_users v
        LEFT JOIN interactions i ON i.target_id = v.id AND i.user_id = :my_id
        WHERE v.id != :my_id 
          AND are_friends(v.id, :my_id) = FALSE
          AND (i.id IS NULL OR i.action = \'maybe\')
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



    public function handleLike(int $my_id, int $target_id): bool
{
    $db = $this->database->connect();
    
    try {
        $db->beginTransaction();

        // 1. Zapisujemy Twojego lajka
        $stmt = $db->prepare('
            INSERT INTO interactions (user_id, target_id, action) 
            VALUES (?, ?, \'like\')
            ON CONFLICT (user_id, target_id) DO UPDATE SET action = \'like\'
        ');
        $stmt->execute([$my_id, $target_id]);

        // 2. Sprawdzamy czy ta druga osoba już Cię wcześniej polubiła
        $stmtCheck = $db->prepare('
            SELECT 1 FROM interactions 
            WHERE user_id = ? AND target_id = ? AND action = \'like\'
        ');
        $stmtCheck->execute([$target_id, $my_id]);
        $isMatch = (bool)$stmtCheck->fetch();

        // 3. Jeśli jest MATCH – dodajemy do znajomych
        if ($isMatch) {
            $stmtFriend = $db->prepare('
                INSERT INTO friends (user_id_1, user_id_2, status) 
                VALUES (?, ?, \'accepted\')
                ON CONFLICT DO NOTHING
            ');
            $stmtFriend->execute([$my_id, $target_id]);
        }

        $db->commit();
        return $isMatch; // Zwracamy informację do kontrolera, czy był match (żeby np. pokazać powiadomienie)

    } catch (\Exception $e) {
        $db->rollBack();
        error_log($e->getMessage());
        return false;
    }
}



} 