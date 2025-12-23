<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/MatchRepository.php';

class DiscoverController extends AppController  {

    private $matchRepository;

    public function __construct() {
            $this->matchRepository = new MatchRepository();
    }


    public function swipe(){
        $this->requireLogin();
        if (!$this->isPost()) {
            return $this->render('discover');
        }
        $my_id = $_SESSION['user_id'];
        $target_id = $_POST['target_id'];
        $action = $_POST['action']; // 'like' lub 'dislike'

        $dbAction = ($action === 'like') ? 'like' : 'dislike';
        $this->matchRepository->addInteraction($my_id, $target_id, $action);

       if ($dbAction === 'like') {
            if ($this->matchRepository->checkMatch($my_id, $target_id)) {
                $this->matchRepository->addFriend($my_id, $target_id);
                // Opcjonalnie: przekieruj na stronę "Mamy Match!"
                // return $this->render('match_success', ['user_id' => $targetId]);
                header('Location: /discover?match=true');
                exit;
            }
        }
        header("Location: /discover");

    }
    public function discover() {
        $this->requireLogin();
        $my_id = $_SESSION['user_id'];

        $user = $this->matchRepository->getRandomMatch($my_id);
        return $this->render("discover", ['user' => $user]);
        }
    

}