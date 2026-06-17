<?php
namespace App\Core;

abstract class AbstractUser {
    protected $name;
    protected $email;
    protected $password;
    protected $deleted_at = null;

    public function __construct($name, $email, $password, $deleted_at = null) {
        $this->name = $name;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->deleted_at = $deleted_at;
    }

    public function delete() {
        $this->deleted_at = date('Y-m-d H:i:s');
    }

    public function restore() {
        $this->deleted_at = null;
    }

    public function isTrashed() {
        return $this->deleted_at !== null;
    }

    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
    abstract public function userRole();
}