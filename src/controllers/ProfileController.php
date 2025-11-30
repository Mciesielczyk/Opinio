<?php

require_once 'AppController.php';
require_once __DIR__.'/../repository/UserRepository.php';
require_once __DIR__.'/../helpers/auth.php';

class ProfileController extends AppController  {
    private $userRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    public function profile() {
        $this->showProfile();
        //return $this->render("profile");
        }

    public function showProfile(){
        $userEmail = checkLogin();


        $user = $this->userRepository->getUserByEmail($userEmail);
        return $this->render("profile", ['user' => $user]);
    }
    

}