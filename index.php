<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/'); //sprawdzamy jaka jest sciezka i usuwamy slashe z poczatku i konca to robi trim
$path = parse_url($path, PHP_URL_PATH); //usuwamy parametry z URL-a, zostawiamy tylko sciezke

// var_dump($path);

Routing::run($path); //wywolujemy metode run klasy Routing i przekazujemy jej sciezke
?>