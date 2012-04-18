<?php
  namespace MoXIM\models;
  
  class Relation extends Node
  {
    const NAME_LENGTH = 32;
    
    public $domain;
    public $name;
    public $range;
    
    public function validate($flags = 0)
    {
      parent::validate($flags);
      $this->domain = Module::validateId($this->domain);
      $this->name = self::validateName($this->name);
      $this->range = Module::validateId($this->range);
    }
    
    static protected function validateName($name)
    {
      return parent::validateString($name, 'name', self::NAME_LENGTH, TRUE);
    }
  }
?>