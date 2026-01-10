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
    $action = $_POST['action'];

    // Jeśli to "like", wywołujemy nową, złożoną metodę
    if ($action === 'like') {
        $isMatch = $this->matchRepository->handleLike($my_id, $target_id);
        
        if ($isMatch) {
            header('Location: /discover?match=true');
            exit;
        }
    } else {
        // Dla 'dislike' lub 'maybe' po prostu zapisujemy interakcję
        $this->matchRepository->addInteraction($my_id, $target_id, $action);
    }

    header("Location: /discover#card-start");
    exit;
}
    
    public function discover() {
        $this->requireLogin();
        $my_id = $_SESSION['user_id'];

        $user = $this->matchRepository->getRandomMatch($my_id);
        $similarity = null;
            
          if ($user) {
        $myScores = $this->userRepository->getUserScores($my_id);
        
        $targetScores = [
            'score_lewa_prawa' => $user['score_lewa_prawa'] ?? 0,
            'score_wladza_wolnosc' => $user['score_wladza_wolnosc'] ?? 0,
            'score_postep_konserwa' => $user['score_postep_konserwa'] ?? 0,
            'score_globalizm_nacjonalizm' => $user['score_globalizm_nacjonalizm'] ?? 0
        ];

        if ($myScores) {
            $similarity = calculateSimilarity($myScores, $targetScores);
        }
    }
        

        return $this->render("discover", [
            'user' => $user,
            'similarity' => $similarity
        ]);
        
    }
        
    

}