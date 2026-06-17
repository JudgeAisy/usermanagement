<?php
namespace App\Core;

trait LoggerTrait {
    public function logMessage($message) {
        echo "<br>[LOG - " . date('Y-m-d H:i:s') . "]: " . $message . "<br>";
    }
}