<?php

class User {
    private $id;
    private $email;
    private $name;
    private $surname;
    private $role;
    private $profilePicture;

    public function __construct($id, $email, $name, $surname, $role, $profilePicture = null) {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->surname = $surname;
        $this->role = $role;
        $this->profilePicture = $profilePicture;
    }
    // Gettery - pozwalają bezpiecznie pobierać dane
    public function getId() { return $this->id; }
    public function getEmail() { return $this->email; }
    public function getName() { return $this->name; }
    public function getSurname() { return $this->surname; }
    public function getRole() { return $this->role; }
    public function getImage() { return $this->image; }

    // Metody pomocnicze (Logika obiektu)
    public function getFullName(): string {
        return $this->name . ' ' . $this->surname;
    }

    public function isAdmin(): bool {
        return $this->role === 'admin';
    }

    public function getProfilePicture(): string {
        // Jeśli w bazie jest pusto, zwróć domyślny awatar
        if (!$this->profilePicture) {
            return 'public/uploads/avatars/avatar.jpg';
        }
        return 'public/uploads/avatars/' . $this->profilePicture;
    }
}