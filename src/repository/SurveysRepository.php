<?php

require_once 'Repository.php';

class SurveysRepository extends Repository
{

    public function getSurveys(): ?array
    {
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM surveys 
        ');
        $stmt->execute();
        $Surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $Surveys;
    }

    public function getQuestionsFromSurvey(int $id): ?array
    {
        // 1. Poprawny kod SQL z użyciem placeholder'a (?)
        $stmt = $this->database->connect()->prepare('
            SELECT
                 A.id,
                A.question_text
            FROM
                questions AS A
            JOIN
                surveys AS B
            ON
                A.survey_id = B.id
            WHERE 
                B.id = ?; 
        ');

        // 2. Binding (zabezpieczenie) wartości $id i wykonanie zapytania
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        
        // 3. Użycie fetchAll() do pobrania wszystkich wyników
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Zwrócenie wyników lub null, jeśli nie znaleziono pytań
        return $questions ?: null;
    }
    

    public function getSurveyById(int $id): ?array {
        $stmt = $this->database->connect()->prepare("
            SELECT * FROM surveys WHERE id = :id
        ");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    
    // w SurveysRepository.php
public function saveUserAnswers(int $userId, array $answers): void {
    $stmt = $this->database->connect()->prepare('
        INSERT INTO user_answers (user_id, question_id, answer_value, created_at)
        VALUES (:user_id, :question_id, :answer_value, NOW())
    ');

    foreach ($answers as $a) {
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':question_id', $a['question_id']);
        $stmt->bindParam(':answer_value', $a['value']);
        $stmt->execute();
    }
}

} 