<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/SurveysRepository.php';

class QuestionsController extends AppController  {

    private $surveysRepository;
 
    
    public function __construct() 
    {
         $this->surveysRepository = new SurveysRepository();
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

}