<?php
  namespace MoXIM\models;

  class Relationship extends Node
  {
    public $source;
    public $relation;
    public $target;

    public function validate($flags = 0)
    {
      parent::validate();
      //$this->source = Node::validateId($this->source);
      $this->relation = Relation::validateId($this->relation);
      //$this->target = Node::validateId($this->target);
    }

  }
?>
