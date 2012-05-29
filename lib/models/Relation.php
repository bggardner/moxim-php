<?php
  namespace MoXIM\models;
  
  class Relation extends Node
  {
    const NAME_LENGTH = 32;
    
    public $source;
    public $name;
    public $target;
    
    public function validate($flags = 0)
    {
      parent::validate($flags);
      $this->source = Module::validateId($this->source);
      $this->name = self::validateName($this->name);
      $this->target = Module::validateId($this->target);
    }
    
    static protected function validateName($name)
    {
      return parent::validateString($name, 'name', self::NAME_LENGTH, TRUE);
    }
  }
?>