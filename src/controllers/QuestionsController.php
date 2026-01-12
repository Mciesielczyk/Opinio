<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/SurveysRepository.php';

class QuestionsController extends AppController
{

    private $surveysRepository;
    private $userRepository;

    public function __construct()
    {
        $this->surveysRepository = new SurveysRepository();
        $this->userRepository = UserRepository::getInstance();
    }


    public function questions()
    {
        $this->requireLogin();
        $userId = $_SESSION['user_id'];
        $allSurveys = $this->surveysRepository->getSurveys();

        foreach ($allSurveys as &$survey) {
            $survey['is_completed'] = $this->surveysRepository->isSurveyFinished($userId, $survey['id']);
        }

        usort($allSurveys, function ($a, $b) {
            return $a['is_completed'] <=> $b['is_completed'];
        });

        return $this->render("questions", ['Surveys' => $allSurveys]);
    }


    public function view()
    {
        $this->requireLogin();
        if (!isset($_GET['id'])) {
            die("Brak ID ankiety");
        }

        $id = intval($_GET['id']);
        $survey = $this->surveysRepository->getSurveyById($id);
        $questions = $this->surveysRepository->getQuestionsFromSurvey($id);

        if ($this->surveysRepository->isSurveyFinished($_SESSION['user_id'], $id)) {
            header("Location: /questions?message=survey_completed");
            exit;
        }

        return $this->render("survey", [
            'survey' => $survey,
            'questions' => $questions
        ]);
    }

    public function saveSurvey()
    {
        header('Content-Type: application/json');
        $this->requireLogin();
        $json = json_decode(file_get_contents("php://input"), true);
        $data = $json['payload'] ?? null;
        if (!$data || !isset($data['answers'])) {
            echo json_encode(['status' => 'error', 'message' => 'Brak danych']);
            exit;
        }
        $answers = $data['answers'];
        $surveyId = $data['survey_id'];
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            echo json_encode(['status' => 'error', 'message' => 'Niezalogowany']);
            exit;
        }
        $userScore = $this->userRepository->getUserScores($userId);
        if (!$userScore) {
            $userScore = [
                'score_lewa_prawa' => 0,
                'score_wladza_wolnosc' => 0,
                'score_postep_konserwa' => 0,
                'score_globalizm_nacjonalizm' => 0
            ];
        }

        $scale = 0.2;

        foreach ($answers as $questionName => $value) {
            $questionId = (int) str_replace('answer', '', $questionName);
            $q = $this->surveysRepository->getQuestionByIdAll($questionId);
            error_log("111dupaaasaasaa: " . print_r($q, true));
            if (!$q) continue;
            $delta = ((int)$value - 3) / 2;
            $userScore['score_lewa_prawa']        += $q['score_lewa_prawa'] * $delta * $scale;
            $userScore['score_wladza_wolnosc']    += $q['score_wladza_wolnosc'] * $delta * $scale;
            $userScore['score_postep_konserwa']   += $q['score_postep_konserwa'] * $delta * $scale;
            $userScore['score_globalizm_nacjonalizm'] += $q['score_globalizm_nacjonalizm'] * $delta * $scale;
            error_log("dupaaaa: " . print_r($q, true));
        }
        //var_dump($scoreLewaPrawa, $scoreWladzaWolnosc, $scorePostepKonserwa, $scoreGlobalizmNacjonalizm);
        error_log("Wyniki PO PRZELICZENIU: " . print_r($userScore, true));
        $this->userRepository->upsertUserScore(
            $userId,
            $userScore['score_lewa_prawa'],
            $userScore['score_wladza_wolnosc'],
            $userScore['score_postep_konserwa'],
            $userScore['score_globalizm_nacjonalizm']
        );
        $this->surveysRepository->markSurveyAsFinished($userId, $surveyId);
        echo json_encode(['status' => 'ok']);
        exit;
    }
}
