<?php

require_once __DIR__.'/../../Database.php';
// Nie wykonuje żadnych zapytań, tylko udostępnia $this->database innym repozytoriom.
class Repository {
    protected $database; // przechowuje instancję klasy Database

    public function __construct()
    {         // tworzymy nowe połączenie z bazą danych

        $this->database = new Database();
    }
}