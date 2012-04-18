<?php
  namespace MoXIM\models;
  
  class Module extends Node
  {
    const NAME_LENGTH = 32;
    
    public $name;
    
    public function validate($flags = 0)
    {
      parent::validate($flags);
      $this->name = self::validateName($this->name);
    }
    
    static protected function validateName($name)
    {
      return parent::validateString($name, 'name', self::NAME_LENGTH, TRUE);
    }
  }
?>
