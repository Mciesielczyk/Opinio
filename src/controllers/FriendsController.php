<?php

require_once 'AppController.php';
require_once __DIR__.'/../helpers/auth.php';

class FriendsController extends AppController  {

    public function friends() {
        $user = checkLogin();
        echo "Witaj, " .$user;
        return $this->render("friends");
        }
    

}