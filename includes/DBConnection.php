<?php

class DBConnection {

    private $con;

    public function getDatabaseConnection() {
        require 'db_info.php';
        $this->con=new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
        return $this->con;
    }
}
?>
