<?php

require_once 'AppController.php';

class FriendsController extends AppController  {

    public function friends() {
        return $this->render("friends");
        }
    

}