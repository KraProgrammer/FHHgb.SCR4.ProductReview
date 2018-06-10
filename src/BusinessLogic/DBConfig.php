<?php 

namespace BusinessLogic;

interface DBConfig {
    public function getServer();
    public function getUserName();
    public function getPassword();
    public function getDatabase();
}