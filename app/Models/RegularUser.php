<?php
namespace App\Models;

use App\Core\AbstractUser;

class RegularUser extends AbstractUser {
    public function __construct($name, $email, $password, $deleted_at = null) {
        parent::__construct($name, $email, $password, $deleted_at);
    }

    public function userRole() {
        return "Regular User";
    }
}