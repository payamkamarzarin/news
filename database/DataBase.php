<?php

namespace database;

use PDO;
use PDOException;

class DataBase{
    private $connection;
    private $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
    private $dbName = DB_NAME;
    private $dbHost = DB_HOST;
    private $dbUser = DB_USER;
    private $dbPass = DB_PASS;
    private $dbPort = DB_PORT;

    function __construct(){
        try {
            $dsn = "mysql:host=".$this->dbHost.";port=".$this->dbPort.";dbname=".$this->dbName;$this->connection = new PDO($dsn, $this->dbUser, $this->dbPass, $this->options);
            echo "Connected successfully";
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

}