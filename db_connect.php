<?php

//a class file to connect to db

class DB_Connect{

    private $conn;


    //connecting to database
    public function connect(){
        require_once __DIR__.'db_config.php';

        //connecting to mysql database
        $this -> conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);

        //return database handler
        return $this->conn;
    }

}


?>