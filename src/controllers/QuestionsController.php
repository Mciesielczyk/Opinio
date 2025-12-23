<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/SurveysRepository.php';

class QuestionsController extends AppController  {

    private $surveysRepository;
    private $userRepository;
    
    public function __construct() 
    {
         $this->surveysRepository = new SurveysRepository();
         $this->userRepository = UserRepository::getInstance();
    }


    public function questions() {
$this->requireLogin();
        $Surveys =  $this->surveysRepository->getSurveys();
        return $this->render("questions", ['Surveys' => $Surveys]);
    }
    

        public function view() {
            $this->requireLogin();
        if (!isset($_GET['id'])) {
            die("Brak ID ankiety");
        }

        $id = intval($_GET['id']);

        $survey = $this->surveysRepository->getSurveyById($id);
        $questions = $this->surveysRepository->getQuestionsFromSurvey($id);

        return $this->render("survey", [
            'survey' => $survey,
            'questions' => $questions
        ]);
    }

public function saveSurvey() {
    header('Content-Type: application/json');
    $this->requireLogin();

    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data || !isset($data['answers'])) {
        echo json_encode(['status' => 'error', 'message' => 'Brak danych']);
        exit;
    }

    $answers = $data['answers'];

    // pobieramy id użytkownika
    $userId = $_SESSION['user_id'] ?? null; 
    if (!$userId) {
        echo json_encode(['status' => 'error', 'message' => 'Niezalogowany']);
        exit;
    }
    // inicjalizacja wyników dla osi
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

    // iterujemy po odpowiedziach
    foreach ($answers as $questionName => $value) {
        
        $questionId = (int) str_replace('answer', '', $questionName);
        $q = $this->surveysRepository->getQuestionByIdAll($questionId);
        error_log("111dupaaasaasaa: " . print_r($q, true));

        if (!$q) continue;
        $delta = ((int)$value - 3) / 2;
        // w prostym wariancie dodajemy każdą odpowiedź do każdej osi
        $userScore['score_lewa_prawa']        += $q['score_lewa_prawa'] * $delta * $scale;
        $userScore['score_wladza_wolnosc']    += $q['score_wladza_wolnosc'] * $delta * $scale;
        $userScore['score_postep_konserwa']   += $q['score_postep_konserwa'] * $delta * $scale;
        $userScore['score_globalizm_nacjonalizm'] += $q['score_globalizm_nacjonalizm'] * $delta * $scale;
        error_log("dupaaaa: " . print_r($q, true));

    }
//var_dump($scoreLewaPrawa, $scoreWladzaWolnosc, $scorePostepKonserwa, $scoreGlobalizmNacjonalizm);
error_log("Wyniki PO PRZELICZENIU: " . print_r($userScore, true));
    // zapis do bazy 1:1 dla użytkownika
    $this->userRepository->upsertUserScore(
        $userId,
        $userScore['score_lewa_prawa'],
        $userScore['score_wladza_wolnosc'],
        $userScore['score_postep_konserwa'],
        $userScore['score_globalizm_nacjonalizm']
    );

    echo json_encode(['status' => 'ok']);
    exit;
}


}