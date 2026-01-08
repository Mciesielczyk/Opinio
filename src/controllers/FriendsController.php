<?php

require_once 'AppController.php';
require_once __DIR__.'/../helpers/auth.php';
require_once __DIR__.'/../helpers/CalculatorService.php';

require_once __DIR__.'/../repository/FriendsRepository.php';
require_once __DIR__.'/../repository/UserRepository.php';

class FriendsController extends AppController  {

    private $friendsRepository;
        private $userRepository;
    public function __construct() 
    {
         $this->friendsRepository = new FriendsRepository();
          $this->userRepository = UserRepository::getInstance();
         
    }

public function friendsSearch() {
    $this->requireLogin();

    if(!$this->isPost()) {
        return header("Location: /friends");
    }

    $userId = $_SESSION['user_id'];
    $searchTerm = $_POST['searchbar'] ?? '';
    
    // Pobieramy Twoje wyniki do porównania
    $myScores = $this->userRepository->getUserScores($userId);
    $friends = $this->friendsRepository->searchFriends($userId, $searchTerm);

    // MUSISZ dodać te obliczenia również tutaj:
    foreach ($friends as &$friend) {
        $hasRealScores = isset($friend['score_lewa_prawa']) && $friend['score_lewa_prawa'] !== null;
        $friendScores = [
            'score_lewa_prawa' => (float)($friend['score_lewa_prawa'] ?? 0),
            'score_wladza_wolnosc' => (float)($friend['score_wladza_wolnosc'] ?? 0),
            'score_postep_konserwa' => (float)($friend['score_postep_konserwa'] ?? 0),
            'score_globalizm_nacjonalizm' => (float)($friend['score_globalizm_nacjonalizm'] ?? 0),
            'has_data' => $hasRealScores
        ];
        $friend['similarity'] = calculateSimilarity($myScores, $friendScores);
    }

    // Używamy małej litery 'friends', żeby pasowało do widoku i metody friends()
    return $this->render("friends", [
        'friends' => $friends, 
        'searchTerm' => $searchTerm
    ]);
}

public function friends() {
    $this->requireLogin();
    $myId = $_SESSION['user_id'];
    
    $myScores = $this->userRepository->getUserScores($myId);
    $friends = $this->friendsRepository->getFriendsList($myId);

    foreach ($friends as &$friend) {
        $hasRealScores = isset($friend['score_lewa_prawa']) && $friend['score_lewa_prawa'] !== null;
        $friendScores = [
            'score_lewa_prawa' => (float)($friend['score_lewa_prawa'] ?? 0),
            'score_wladza_wolnosc' => (float)($friend['score_wladza_wolnosc'] ?? 0),
            'score_postep_konserwa' => (float)($friend['score_postep_konserwa'] ?? 0),
            'score_globalizm_nacjonalizm' => (float)($friend['score_globalizm_nacjonalizm'] ?? 0),
            'has_data' => $hasRealScores
            
        ];
        $friend['similarity'] = calculateSimilarity($myScores, $friendScores);
    }
    
    $this->render('friends', ['friends' => $friends]);
}
}