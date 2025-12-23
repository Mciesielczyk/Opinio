<?php

require_once 'Repository.php';

class MessageRepository extends Repository
{


    public function getConversation(int $user1, int $user2): array {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM messages 
            WHERE (sender_id = :u1 AND receiver_id = :u2)
               OR (sender_id = :u2 AND receiver_id = :u1)
            ORDER BY created_at ASC
        ');
        $stmt->execute(['u1' => $user1, 'u2' => $user2]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function sendMessage(int $from, int $to, string $text) {
        $stmt = $this->database->connect()->prepare('
            INSERT INTO messages (sender_id, receiver_id, message)
            VALUES (?, ?, ?)
        ');
        $stmt->execute([$from, $to, $text]);
    }

} 