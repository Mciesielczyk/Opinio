<?php
require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/DashboardController.php';
require_once 'src/controllers/QuestionsController.php';
require_once 'src/controllers/FriendsController.php';
require_once 'src/controllers/DiscoverController.php';
require_once 'src/controllers/ProfileController.php';
require_once 'src/controllers/MessageController.php';
require_once 'src/controllers/AdminController.php';

class Routing {

    public static $routes = [ //tablica asocjacyjna przechowujaca sciezki i odpowiadajace im kontrolery i akcje
        'login' => [ //sciezka, wartosci - kontroler i akcja czyli gdzie i jaka metoda
            'controller' => 'SecurityController',//inaczej slownik
            'action' => 'login'
        ],
        'register' => [
            'controller' => 'SecurityController',
            'action' => 'register'
        ],
        'dashboard' => [
            'controller' => 'DashboardController',
            'action' => 'index'
        ],
        'questions' => [
            'controller' => 'QuestionsController',
            'action' => 'questions'
        ],
        'friends' => [
            'controller' => 'FriendsController',
            'action' => 'friends'
        ],
        'discover' => [
            'controller' => 'DiscoverController',
            'action' => 'discover'
        ],
        'swipe' => [
        'controller' => 'DiscoverController',
        'action' => 'swipe'
        ],
        'profile' => [
            'controller' => 'ProfileController',
            'action' => 'profile'
        ],
        'survey' => [
        'controller' => 'QuestionsController',
        'action' => 'view'
        ],
        'logout' => [
        'controller' => 'SecurityController',
        'action' => 'logout'
        ],
        'saveSurvey' => [
        'controller' => 'QuestionsController',
        'action' => 'saveSurvey'
        ],
        'friendsSearch' => [
        'controller' => 'FriendsController',
        'action' => 'friendsSearch'
        ],
        'chat' => [
            'controller' => 'MessageController',
            'action' => 'chat'
        ],
        'adminPanel' => [
            'controller' => 'AdminController', 
            'action' => 'adminPanel'
        ],
        'editSurvey' => [
            'controller' => 'AdminController', 
            'action' => 'editSurvey'
        ],
        'deleteUser' => [
            'controller' => 'AdminController', 
            'action' => 'deleteUser'
        ],
        'updateQuestionParams' => [
            'controller' => 'AdminController', 
            'action' => 'updateQuestionParams'
        ],
        'addSurvey' => [
        'controller' => 'AdminController',
        'action' => 'addSurvey'
      ],
      'addQuestion' => [
    'controller' => 'AdminController',
    'action' => 'addQuestion'
    ],
    'deleteSurvey' => [
        'controller' => 'AdminController',
        'action' => 'deleteSurvey'
      ],
      'changeRole' => [
    'controller' => 'AdminController',
    'action' => 'changeRole'
],
'updateQuestion' => [
    'controller' => 'AdminController',
    'action' => 'updateQuestion'
],'deleteQuestion' => [
    'controller' => 'AdminController',
    'action' => 'deleteQuestion'
]

     ];


    public static function run(string $path) {
        //TODO na podstawie sciezki sprawdzamy jaki HTML zwrocic
        switch ($path) {
            case 'dashboard': //fallthrough nie ma breaka wiec przechodzi do nastepnego case'a
            case 'login':
            case 'register':
            case 'questions':
            case 'friends':
            case 'discover':
            case 'profile':
            case 'survey':
            case 'logout':
            case 'saveSurvey':
            case 'swipe':
            case 'friendsSearch':
            case 'chat':
            case 'adminPanel':
            case 'editSurvey':
            case 'deleteUser':
            case 'updateQuestionParams':
            case 'addSurvey':
            case 'addQuestion':
            case 'deleteSurvey':
            case 'changeRole':
                case 'updateQuestion':
            case 'deleteQuestion':
                $controller = Routing::$routes[$path]['controller'];
                $action = Routing::$routes[$path]['action'];

                $controllerObj = new $controller; //tworzymy obiekt klasy kontrolera
                $controllerObj->$action(); //wywolujemy metode akcji na obiekcie kontrolera
                break;

            default:
                include 'public/views/404.html';
                break;
        } 
    }
}