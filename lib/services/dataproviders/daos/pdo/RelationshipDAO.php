<?php

  namespace MoXIM\services\dataproviders\daos\pdo;

  use PDO;

  class RelationshipDAO extends NodeDAO
  {
    public function __construct($ds)
    {
      $this->columns["source"] = PDO::PARAM_INT;
      $this->columns["relation"] = PDO::PARAM_INT;
      $this->columns["target"] = PDO::PARAM_INT;
      parent::__construct($ds);
    }
  }

?>
