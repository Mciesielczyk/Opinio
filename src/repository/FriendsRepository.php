<?php

require_once 'Repository.php';

class FriendsRepository extends Repository
{
public function getFriendsList(int $user_id): array
{
    $stmt = $this->database->connect()->prepare('
        SELECT 
            friend_id AS id, -- zmieniamy nazwę na "id", żeby pasowała do widoku
            name, surname, profile_picture, description, location,
            score_lewa_prawa, score_wladza_wolnosc, score_postep_konserwa, score_globalizm_nacjonalizm
        FROM v_friends_details
        WHERE (requester_id = :user_id OR receiver_id = :user_id) 
          AND friend_id != :user_id 
          AND status = \'accepted\'
    ');
    
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function searchFriends(int $user_id, string $searchTerm): array
    {
        $stmt = $this->database->connect()->prepare('
            SELECT 
                u.id, u.name, u.surname, u.profile_picture, u.description, u.location,
                us.score_lewa_prawa, 
                us.score_wladza_wolnosc, 
                us.score_postep_konserwa, 
                us.score_globalizm_nacjonalizm
            FROM users u
            INNER JOIN friends f ON (f.user_id_1 = u.id OR f.user_id_2 = u.id)
            /* TEGO BRAKOWAŁO W SEARCH: */
            LEFT JOIN user_scores us ON u.id = us.user_id
            WHERE (f.user_id_1 = :user_id OR f.user_id_2 = :user_id) 
              AND u.id != :user_id 
              AND f.status = \'accepted\'
              AND (u.name ILIKE :searchTerm OR u.surname ILIKE :searchTerm)
        ');
        $likeTerm = '%' . $searchTerm . '%';
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':searchTerm', $likeTerm, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}