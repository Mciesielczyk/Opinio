<?php

require_once 'Repository.php';

class MatchRepository extends Repository
{
public function getRandomMatch(int $my_id): ?array
    {
        $stmt = $this->database->connect()->prepare('
            SELECT u.id, u.name, u.surname,
            u.profile_picture,u.description, u.location
            FROM users u
            LEFT JOIN interactions i ON i.target_id = u.id AND i.user_id = :my_id
            LEFT JOIN friends f ON (f.user_id_1 = u.id AND f.user_id_2 = :my_id) 
                                OR (f.user_id_2 = u.id AND f.user_id_1 = :my_id)
            WHERE u.id != :my_id 
            AND i.id IS NULL 
            AND f.id IS NULL
            ORDER BY RANDOM() 
            LIMIT 1
        ');
    $stmt->bindParam(':my_id', $my_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user ?: null;
    }
  
    
    public function addInteraction(int $user_id, int $target_id, string $action): void
    {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO interactions (user_id, target_id, action) 
            VALUES (:user_id, :target_id, :action)
        ');
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':target_id', $target_id, PDO::PARAM_INT);
        $stmt->bindParam(':action', $action, PDO::PARAM_STR);
        $stmt->execute();
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