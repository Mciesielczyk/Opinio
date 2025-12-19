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
    $userId = $_SESSION['user_id'];

    // inicjalizacja wyników dla osi
    $scoreLewaPrawa = 1;
    $scoreWladzaWolnosc = 2;
    $scorePostepKonserwa = 0;
    $scoreGlobalizmNacjonalizm = 4;

    // iterujemy po odpowiedziach
    foreach ($answers as $questionName => $value) {
        // w prostym wariancie dodajemy każdą odpowiedź do każdej osi
        $scoreLewaPrawa += $value;
        $scoreWladzaWolnosc += $value;
        $scorePostepKonserwa += $value;
        $scoreGlobalizmNacjonalizm += $value;
    }
var_dump($scoreLewaPrawa, $scoreWladzaWolnosc, $scorePostepKonserwa, $scoreGlobalizmNacjonalizm);

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