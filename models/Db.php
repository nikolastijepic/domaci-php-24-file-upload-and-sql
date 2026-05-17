<?php

class Db {
    public $connection;

    public function __construct() {
        $this->connection = mysqli_connect("localhost", "root", "ServBay.dev", "php23");
    }
}