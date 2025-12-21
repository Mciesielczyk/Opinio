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

        $Surveys =  $this->surveysRepository->getSurveys();
        return $this->render("questions", ['Surveys' => $Surveys]);
    }
    

        public function view() {
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
    $scoreLewaPrawa = 0;
    $scoreWladzaWolnosc = 0;
    $scorePostepKonserwa = 0;
    $scoreGlobalizmNacjonalizm = 0;

    // iterujemy po odpowiedziach
    foreach ($answers as $questionName => $value) {
        // w prostym wariancie dodajemy każdą odpowiedź do każdej osi
        $scoreLewaPrawa += (int)$value;
        $scoreWladzaWolnosc += (int)$value;
        $scorePostepKonserwa += (int)$value;
        $scoreGlobalizmNacjonalizm += (int)$value;
    }
//var_dump($scoreLewaPrawa, $scoreWladzaWolnosc, $scorePostepKonserwa, $scoreGlobalizmNacjonalizm);

    // zapis do bazy 1:1 dla użytkownika
    $this->userRepository->upsertUserScore(
        $userId,
        $scoreLewaPrawa,
        $scoreWladzaWolnosc,
        $scorePostepKonserwa,
        $scoreGlobalizmNacjonalizm
    );

    echo json_encode(['status' => 'ok']);
    exit;
}


}