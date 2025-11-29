<?php

require_once 'AppController.php';

class QuestionsController extends AppController  {

    public function questions() {
        return $this->render("questions");
        }
    

}