<?php

  namespace MoXIM\services\dataproviders\daos\pdo;

  use PDO;

  abstract class AssignmentDAO extends NodeDAO
  {
    const TABLE_NAME = 'moxim_assignments';

    public function __construct($ds)
    {
      $this->columns["module"] = PDO::PARAM_INT;
      $this->columns["node"] = PDO::PARAM_INT;
      $this->columns["value"] = PDO::PARAM_STR;
      parent::__construct($ds);
    }
  }

?>
