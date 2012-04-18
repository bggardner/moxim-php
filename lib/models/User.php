<?php
  namespace MoXIM\models;
  
  class User extends Node
  {
    const NAME_LENGTH = 32;
    const PASSWORD_LENGTH = 32;
    
    public $name;
    public $password;
    public $active;
    public $last_login;
  }
?>