<?php
namespace App\Core;

abstract class AbstractUser {
    protected $name;
    protected $email;
    protected $password;

    public function __construct($name, $email, $password) {
        $this->name = $name;
        $this->email = $email;
        // Şifreyi güvenli hale getiriyoruz
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
    
    abstract public function userRole();
}