<?php

require_once 'AppController.php';

class DiscoverController extends AppController  {

    public function discover() {
        return $this->render("discover");
        }
    

}