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
    
  
public function uploadImage() {
     $this->requireLogin();
    $type = $_POST['image_type']; // 'profile' lub 'background'
    
    if ($this->isPost() && is_uploaded_file($_FILES['file']['tmp_name'])) {
        $subFolder = ($type === 'background') ? 'backgrounds/' : 'avatars/';
        $uploadDir = dirname(__DIR__, 2) . '/public/uploads/' . $subFolder;
        $fileName = uniqid() . '_' . $_FILES['file']['name'];
        $fullPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $fullPath)) {
            $userEmail = $_SESSION['username'];

            if ($type === 'background') {
                $this->userRepository->updateBackgroundPicture($userEmail, $fileName);
            } else {
                $this->userRepository->updateProfilePicture($userEmail, $fileName);
            }
            
            header("Location: /profile");
            exit();
        }
    }
}

public function updateProfileData() {
    $this->requireLogin();
    
    if (!$this->isPost()) {
        header("Location: /profile");
        exit();
    }

    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $description = $_POST['description'];
    $userEmail = $_SESSION['username']; // upewnij się czy używasz 'username' czy 'user_email'

    $this->userRepository->updateUserDetails($userEmail, $name, $surname, $description);

    header("Location: /profile?success=updated");
    exit();
}


}