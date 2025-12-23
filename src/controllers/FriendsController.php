<?php

require_once 'AppController.php';
require_once __DIR__.'/../helpers/auth.php';
require_once __DIR__.'/../repository/FriendsRepository.php';
class FriendsController extends AppController  {

    private $friendsRepository;
    public function __construct() 
    {
         $this->friendsRepository = new FriendsRepository();
    }

    public function friendsSearch() {
        $this->requireLogin();

        if(!$this->isPost()) {
            return $this->render("friends");
        }
        $userId = $_SESSION['user_id'];
        $searchTerm = $_POST['searchbar'] ?? '';
        $friends = $this->friendsRepository->searchFriends($userId, $searchTerm);
        return $this->render("friends", ['Friends' => $friends , 'searchTerm' => $searchTerm]);
        
        }

        public function friends(){
            
        $this->requireLogin();
        $userId = $_SESSION['user_id'];
        $friends = $this->friendsRepository->getFriendsList($userId);
        return $this->render("friends", ['Friends' => $friends]);
        }
}