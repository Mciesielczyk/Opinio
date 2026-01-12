<?php
require_once 'AppController.php';
require_once __DIR__ . '/../repository/MessageRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class MessageController extends AppController
{
    private $messageRepository;
    private $userRepository;
    public function __construct()
    {
        $this->messageRepository = new MessageRepository();
        $this->userRepository = UserRepository::getInstance();
    }

    public function chat()
    {
        $this->requireLogin();
        $myId = $_SESSION['user_id'];
        $receiverId = $_GET['id']; 

        if ($this->isPost()) {
            $text = $_POST['message'];
            if (!empty($text)) {
                $this->messageRepository->sendMessage($myId, $receiverId, $text);
            }
            header("Location: /chat?id=" . $receiverId);
            exit;
        }
        $receiver = $this->userRepository->getUserById($receiverId);

        $messages = $this->messageRepository->getConversation($myId, $receiverId);
        return $this->render(
            'chat',
            [
                'messages' => $messages,
                'receiver' => $receiver,
                'receiverId' => $receiverId
            ]
        );
    }


    public function getMessagesJson()
    {
        $this->requireLogin();
        $myId = $_SESSION['user_id'];
        $receiverId = $_GET['id'];
        $messages = $this->messageRepository->getConversation($myId, $receiverId);
        header('Content-type: application/json');
        echo json_encode($messages);
    }

    public function sendMessageAjax()
    {
        $this->requireLogin();
        $myId = $_SESSION['user_id'];
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data && !empty($data['message'])) {
            $this->messageRepository->sendMessage($myId, $data['receiverId'], $data['message']);
            echo json_encode(['status' => 'success']);
        }
    }
}
