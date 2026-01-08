<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/MatchRepository.php';
require_once __DIR__.'/../repository/UserRepository.php';
require_once __DIR__.'/../helpers/CalculatorService.php';
class DiscoverController extends AppController  {

    private $matchRepository;
    private $userRepository;
    public function __construct() {
            $this->matchRepository = new MatchRepository();
            $this->userRepository = UserRepository::getInstance();
            
    }


public function swipe() {
    $this->requireLogin();
    
    $my_id = $_SESSION['user_id'];
    $target_id = $_POST['target_id'];
    $action = $_POST['action']; // 'like', 'dislike' lub 'maybe'

    // Zapisujemy dokładnie to, co przyszło z przycisku
    $this->matchRepository->addInteraction($my_id, $target_id, $action);

    if ($action === 'like') {
        if ($this->matchRepository->checkMatch($my_id, $target_id)) {
            $this->matchRepository->addFriend($my_id, $target_id);
            header('Location: /discover?match=true');
            exit;
        }
    }

header("Location: /discover#card-start");
    exit;
}

    
    public function discover() {
        $this->requireLogin();
        $my_id = $_SESSION['user_id'];

        $user = $this->matchRepository->getRandomMatch($my_id);
        $similarity = null;
        $myScores = $this->userRepository->getUserScores($my_id);
            
            // Przygotowujemy wyniki wylosowanego użytkownika
            $targetScores = [
                'score_lewa_prawa' => $user['score_lewa_prawa'] ?? 0,
                'score_wladza_wolnosc' => $user['score_wladza_wolnosc'] ?? 0,
                'score_postep_konserwa' => $user['score_postep_konserwa'] ?? 0,
                'score_globalizm_nacjonalizm' => $user['score_globalizm_nacjonalizm'] ?? 0
            ];

            // Obliczamy podobieństwo tylko jeśli TY masz jakiekolwiek wyniki
            if ($myScores) {
                // Wywołujemy Twoją funkcję (popraw nazwę jeśli jest w klasie np. Calculator::calculate...)
                $similarity = calculateSimilarity($myScores, $targetScores);
            }
        

        return $this->render("discover", [
            'user' => $user,
            'similarity' => $similarity
        ]);
        
    }
        
    

}