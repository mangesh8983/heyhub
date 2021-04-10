<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ConnectDb
 *
 * @author apple
 */
class ConnectDb {
    //put your code here
 
  private $conn;
  
  private $host = 'localhost';
  private $user = 'root';
  private $pass = 'root';
  private $name = 'heyhub';
   
  // The db connection is established in the private constructor.
  public function __construct($type='')
  {
      
      switch ($type)
      {
          default :
              $this->conn = new PDO("mysql:host={$this->host};dbname={$this->name}", $this->user,$this->pass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
      } 
  }
  function getConnection($type='mysql')
  {
      return $this->conn;
  }
}
