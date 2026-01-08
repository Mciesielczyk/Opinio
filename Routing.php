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
],
'deleteQuestion' => [
    'controller' => 'AdminController',
    'action' => 'deleteQuestion'
],
'uploadImage' => [
    'controller' => 'ProfileController',
    'action' => 'uploadImage'
],
'updateSurveyImage' => [
    'controller' => 'AdminController',
    'action' => 'updateSurveyImage'
],
'updateProfileData' => [
    'controller' => 'ProfileController',
    'action' => 'updateProfileData'
],
     ];


    public static function run(string $path) {
        // 1. Sprawdzamy, czy klucz (ścieżka) istnieje w naszej tablicy słownikowej
        if (array_key_exists($path, self::$routes)) {
            
            $route = self::$routes[$path];
            $controllerName = $route['controller'];
            $actionName = $route['action'];

            // 2. Tworzymy obiekt i wywołujemy akcję dynamicznie
            $controllerObj = new $controllerName;
            $controllerObj->$actionName();
            
        } else {
            // 3. Jeśli nie ma takiej trasy w tablicy - wyślij 404
            http_response_code(404);
            include 'public/views/404.html';
            die();
        }
    }
}