<?php
require_once 'AppController.php';
require_once __DIR__.'/../repository/MessageRepository.php';

class MessageController extends AppController {
    private $messageRepository;

    public function __construct() {
        $this->messageRepository = new MessageRepository();
    }

    public function chat() {
        $this->requireLogin();
        $myId = $_SESSION['user_id'];
        $receiverId = $_GET['id']; // Pobieramy ID osoby, z którą piszemy, z adresu URL

        if ($this->isPost()) {
            $text = $_POST['message'];
            if (!empty($text)) {
                $this->messageRepository->sendMessage($myId, $receiverId, $text);
            }
            header("Location: /chat?id=" . $receiverId);
            exit;
        }

        $messages = $this->messageRepository->getConversation($myId, $receiverId);
        return $this->render('chat', ['messages' => $messages, 'receiverId' => $receiverId]);
    }
}