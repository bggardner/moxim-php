<?php
  namespace MoXIM\models;

  class Assignment extends Node
  {
    public $module;
    public $node;
    public $value;

    public function validate()
    {
      parent::validate();
      $this->module = Module::validateId($this->module);
      $this->node = Node::validateId($this->node);
      $this->value = self::validateValue($this->value, FALSE);
    }

    public function validateValue($value, $required = TRUE)
    {
      if ($required && is_null($value))
      {
        throw new RuntimeException('Assignment value is required.');
      }
      return $value;
    }
  }
?>
