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
    $stmt = $this->database->connect()->prepare('
        SELECT * FROM v_survey_questions WHERE survey_id = ?
    ');

    $stmt->execute([$id]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $questions ?: null;
}

    public function getQuestionByIdAll(int $id): ?array
    {
        $stmt = $this->database->connect()->prepare('
            SELECT 
                id, 
                question_text, 
                score_lewa_prawa, 
                score_wladza_wolnosc, 
                score_postep_konserwa, 
                score_globalizm_nacjonalizm 
            FROM questions 
            WHERE id = :id
        ');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $question = $stmt->fetch(PDO::FETCH_ASSOC);

        return $question ?: null;
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


public function addSurvey(string $title, string $image_url = null) {
    // Jeśli image_url nie zostało przekazane (jest null), ustawiamy 'pl.png'
    $finalImage = $image_url ?: 'pl.png';

    $stmt = $this->database->connect()->prepare('
        INSERT INTO surveys (title, image_url)
        VALUES (:title, :image_url)
    ');

    $stmt->execute([
        'title' => $title,
        'image_url' => $finalImage
    ]);
}

public function deleteSurvey(int $id): void {
    $stmt = $this->database->connect()->prepare('DELETE FROM surveys WHERE id = :id');
    $stmt->execute(['id' => $id]);
}

public function addQuestion(int $surveyId, string $text): void {
    $stmt = $this->database->connect()->prepare('
        INSERT INTO questions (survey_id, question_text) VALUES (?, ?)
    ');
    $stmt->execute([$surveyId, $text]);
}

public function updateQuestionScores(int $id, array $scores): void {
    $stmt = $this->database->connect()->prepare('
        UPDATE questions SET 
            score_lewa_prawa = :lp, 
            score_wladza_wolnosc = :ww, 
            score_postep_konserwa = :pk, 
            score_globalizm_nacjonalizm = :gn
        WHERE id = :id
    ');
    $stmt->execute([
        'lp' => $scores['lp'],
        'ww' => $scores['ww'],
        'pk' => $scores['pk'],
        'gn' => $scores['gn'],
        'id' => $id
    ]);
}

public function updateQuestion(int $id, string $text, array $scores): void 
{
    $stmt = $this->database->connect()->prepare('
        UPDATE questions SET 
            question_text = :text,
            score_lewa_prawa = :lp, 
            score_wladza_wolnosc = :ww, 
            score_postep_konserwa = :pk, 
            score_globalizm_nacjonalizm = :gn
        WHERE id = :id
    ');

    $stmt->execute([
        'text' => $text,
        'lp' => $scores['lp'],
        'ww' => $scores['ww'],
        'pk' => $scores['pk'],
        'gn' => $scores['gn'],
        'id' => $id
    ]);
}



public function addQuestionWithScores(int $surveyId, string $text, array $scores): void 
{
    $stmt = $this->database->connect()->prepare('
        INSERT INTO questions (
            survey_id, question_text, 
            score_lewa_prawa, score_wladza_wolnosc, 
            score_postep_konserwa, score_globalizm_nacjonalizm
        ) VALUES (:sid, :txt, :lp, :ww, :pk, :gn)
    ');

    $stmt->execute([
        'sid' => $surveyId,
        'txt' => $text,
        'lp' => $scores['lp'],
        'ww' => $scores['ww'],
        'pk' => $scores['pk'],
        'gn' => $scores['gn']
    ]);
}

public function deleteQuestion(int $id): void 
{
    $stmt = $this->database->connect()->prepare('DELETE FROM questions WHERE id = :id');
    $stmt->execute(['id' => $id]);
} 


public function updateImage(int $id, string $fileName) {
    $stmt = $this->database->connect()->prepare('
        UPDATE surveys SET image_url = :image_url WHERE id = :id
    ');
    $stmt->execute([
        'image_url' => $fileName,
        'id' => $id
    ]);
}


// Sprawdzenie czy rekord istnieje
public function isSurveyFinished(int $userId, int $surveyId): bool {
    $stmt = $this->database->connect()->prepare('
        SELECT 1 FROM user_surveys_completed 
        WHERE user_id = :u AND survey_id = :s
    ');
    $stmt->execute(['u' => $userId, 's' => $surveyId]);
    return (bool)$stmt->fetch();
}

// Dodanie rekordu po wysłaniu ankiety
public function markSurveyAsFinished(int $userId, int $surveyId): void {
    $stmt = $this->database->connect()->prepare('
        INSERT INTO user_surveys_completed (user_id, survey_id) 
        VALUES (:u, :s) ON CONFLICT DO NOTHING
    ');
    $stmt->execute(['u' => $userId, 's' => $surveyId]);
}


}