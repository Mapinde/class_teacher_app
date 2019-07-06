<?php
require_once 'config.php';
class dbConnetion{
    private $dbHost = DB_HOST;
    private $dbUser = DB_USERNAME;
    private $dbPass = DB_PASSWORD;
    private $dbDatabase = DB_DATABASE;
    private $dbConnection;

    public function connect(){
      $mysql_connect_str = "mysql:host={$this->dbHost};dbname={$this->dbDatabase}";
      $this->dbConnection = new PDO($mysql_connect_str, $this->dbUser, $this->dbPass);
      $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $this->dbConnection;  
    }
}