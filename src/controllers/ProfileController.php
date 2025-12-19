<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/UserRepository.php';
require_once __DIR__.'/../helpers/auth.php';

class ProfileController extends AppController  {
    private $userRepository;

    public function __construct() {
                 $this->userRepository = UserRepository::getInstance();
    }

    public function profile() {
        $this->showProfile();
        //return $this->render("profile");
        }

    public function showProfile(){
        $this->requireLogin();
        $userEmail = checkLogin();
        $user = $this->userRepository->getUserByEmail($userEmail);
       
        $scores = $this->userRepository->getUserScores($user['id']);

        return $this->render("profile", ['user' => $user , 'scores' => $scores]);
    }
    

}