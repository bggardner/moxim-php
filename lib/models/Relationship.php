<?php
  namespace MoXIM\models;

  class Relationship extends Node
  {
    public $domain;
    public $relation;
    public $range;

    public function validate($flags = 0)
    {
      parent::validate();
      $this->domain = Node::validateId($this->domain);
      $this->relation = Relation::validateId($this->relation);
      $this->range = Node::validateId($this->relation);
    }

  }
?>
