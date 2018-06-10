<?php 

namespace BusinessLogic;

class DBConfigLocalhost implements DBConfig {
    private $server = 'localhost';
    private $userName = 'root';
    private $password = '';
    private $database = 'productreview';

    public function getServer() {
        return $this->server;
    }

    public function getUserName() {
        return $this->userName;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getDatabase() {
        return $this->database;
    }
}