<?php

require_once 'AppController.php';
require_once __DIR__.'/../helpers/auth.php';

class FriendsController extends AppController  {

    public function friends() {
        $this->requireLogin();

        return $this->render("friends");
        }
    

}