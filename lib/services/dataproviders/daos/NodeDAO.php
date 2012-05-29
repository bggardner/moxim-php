<?php

  namespace MoXIM\services\dataproviders\daos;

  use MoXIM\models\Node;

  abstract class NodeDAO
  {
    public $ds;

    public function __construct($ds)
    {
      $this->ds = $ds;
    }

    abstract public function add(Node $n);
    abstract public function delete(Node $n);
    abstract public function get(Node $n);
    abstract public function update(Node $n);

  }
?>
