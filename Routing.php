<?php
require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/DashboardController.php';
require_once 'src/controllers/QuestionsController.php';
require_once 'src/controllers/FriendsController.php';
require_once 'src/controllers/DiscoverController.php';
require_once 'src/controllers/ProfileController.php';

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