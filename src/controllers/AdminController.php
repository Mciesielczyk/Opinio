<?php
require_once 'AppController.php';
require_once __DIR__.'/../repository/SurveysRepository.php';
require_once __DIR__.'/../repository/UserRepository.php';

class AdminController extends AppController {
    private $surveyRepository;
    private $userRepository;

    public function __construct() {

        $this->surveyRepository = new SurveysRepository();
        $this->userRepository = UserRepository::getInstance();
    }

    public function adminPanel() {
        $this->requireAdmin();
        $users = $this->userRepository->getAllUsers(); // musisz dodać tę metodę w UserRepository
        $surveys = $this->surveyRepository->getSurveys();
        return $this->render("admin_panel", ['users' => $users, 'surveys' => $surveys]);
    }

    public function editSurvey() {
        $this->requireAdmin();
        $id = $_GET['id'];
        


        $questions = $this->surveyRepository->getQuestionsFromSurvey($id);
        $survey = $this->surveyRepository->getSurveyById($id);
        return $this->render('admin_edit_survey', ['questions' => $questions, 'survey' => $survey]);
    }

    public function deleteUser() {
        $this->requireAdmin();
        $id = $_GET['id'];
        $this->userRepository->deleteUser($id);
        header("Location: /adminPanel?message=Użytkownik został usunięty");
        exit;
    }

    public function addSurvey() 
{
    $this->requireAdmin(); // Zabezpieczenie!

    if ($this->isPost()) {
        $title = $_POST['title'];

        if (!empty($title)) {
            $this->surveyRepository->addSurvey($title);
            // Przekierowanie z powrotem do panelu admina
            header("Location: /adminPanel?status=success");
            exit;
        }
    }
    
    header("Location: /adminPanel?status=error");
}

public function addQuestion() 
{
    $this->requireAdmin();

    if ($this->isPost()) {
        $surveyId = (int)$_POST['survey_id'];
        $text = $_POST['question_text'];
        
        $scores = [
            'lp' => (int)$_POST['lp'],
            'ww' => (int)$_POST['ww'],
            'pk' => (int)$_POST['pk'],
            'gn' => (int)$_POST['gn']
        ];

        if (!empty($text) && $surveyId > 0) {
            $this->surveyRepository->addQuestionWithScores($surveyId, $text, $scores);
        }

        header("Location: /editSurvey?id=" . $surveyId);
        exit;
    }
}

    public function deleteSurvey() 
{
    $this->requireAdmin();

    $id = (int)$_GET['id'];
    if ($id > 0) {
        $this->surveyRepository->deleteSurvey($id);
    }

    header("Location: /adminPanel");
    exit;
}


public function changeRole() {
    $this->requireAdmin();

    if ($this->isPost()) {
        $userId = (int)$_POST['user_id'];
        $newRole = $_POST['role'];

        // Zabezpieczenie: admin nie może zmienić roli samemu sobie (opcjonalne)
        if ($userId === $_SESSION['user_id']) {
            header("Location: /adminPanel?error=self_edit");
            exit;
        }

        if (in_array($newRole, ['user', 'admin'])) {
            $this->userRepository->updateUserRole($userId, $newRole);
        }

        header("Location: /adminPanel?status=updated");
        exit;
    }
}

public function updateQuestion() 
{
    $this->requireAdmin();

    if ($this->isPost()) {
        $questionId = (int)$_POST['question_id'];
        $surveyId = (int)$_POST['survey_id']; // potrzebne do powrotu na tę samą stronę
        $text = $_POST['question_text'];
        
        $scores = [
            'lp' => (int)$_POST['lp'],
            'ww' => (int)$_POST['ww'],
            'pk' => (int)$_POST['pk'],
            'gn' => (int)$_POST['gn']
        ];

        if ($questionId > 0 && !empty($text)) {
            $this->surveyRepository->updateQuestion($questionId, $text, $scores);
        }

        header("Location: /editSurvey?id=" . $surveyId . "&status=updated");
        exit;
    }
}
public function deleteQuestion() 
{
    $this->requireAdmin();

    $questionId = (int)$_GET['id'];
    $surveyId = (int)$_GET['survey_id']; // potrzebne do powrotu na tę samą stronę

    if ($questionId > 0) {
        $this->surveyRepository->deleteQuestion($questionId);
    }

    header("Location: /editSurvey?id=" . $surveyId);
    exit;

}

public function updateSurveyImage() {
    if (!$this->isPost()) {
        return header("Location: /adminPanel");
    }

    $surveyId = $_POST['survey_id'];
    
    if (isset($_FILES['survey_image']) && $_FILES['survey_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['survey_image']['tmp_name'];
        $fileName = $_FILES['survey_image']['name'];
        
        // Generowanie unikalnej nazwy pliku
        $newFileName = uniqid() . '_' . $fileName;
        $uploadFolder = './public/uploads/surveys/';
        
        // Upewnij się, że folder istnieje
        if (!is_dir($uploadFolder)) {
            mkdir($uploadFolder, 0777, true);
        }

        $destPath = $uploadFolder . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            // Tutaj wywołujesz repository, aby zapisać $newFileName w bazie
            $this->surveyRepository->updateImage($surveyId, $newFileName);
        }
    }

    header("Location: /editSurvey?id=" . $surveyId);
}

}