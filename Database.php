<?php

require_once "config.php";

class Database {
    private $username;
    private $password;
    private $host;
    private $database;

    public function __construct()
    {
        $this->username = USERNAME;
        $this->password = PASSWORD;
        $this->host = HOST;
        $this->database = DATABASE;
    }

    public function connect()
    {
        try {
            $conn = new PDO(
                "pgsql:host=$this->host;port=5432;dbname=$this->database",
                $this->username,
                $this->password,
                ["sslmode"  => "prefer"]
            );

            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        }
        catch(PDOException $e) {
        error_log("Database Connection Error: " . $e->getMessage());

        // 2. Ustawiamy kod odpowiedzi HTTP na 500 (Internal Server Error)
        http_response_code(500);

        // 3. Wyświetlamy bezpieczny komunikat
        die("Wystąpił błąd techniczny. Prosimy spróbować później.");        }
    }
}