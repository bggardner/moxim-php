<?php

  namespace MoXIM\services\dataproviders\daos\pdo;

  use MoXIM\models\Node;

  interface INodeDAO
  {
    static public function add(Node $n);
    static public function get(Node $n);
    static public function delete(Node $n);
    static public function update(Node $n);
  }

?>
