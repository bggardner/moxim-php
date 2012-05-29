<?php

  namespace MoXIM\services\dataproviders\daos\pdo;

  use PDO;

  abstract class RelationDAO extends NodeDAO
  {
    const TABLE_NAME = 'moxim_relations';

    public function __construct($ds)
    {
      $this->columns["source"] = PDO::PARAM_INT;
      $this->columns["name"] = PDO::PARAM_STR;
      $this->columns["target"] = PDO::PARAM_INT;
      parent::__construct($ds);
    }
  }

?>
