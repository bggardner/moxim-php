<?php

  namespace MoXIM\services\dataproviders\daos\pdo;

  use PDO;

  abstract class ModuleDAO extends NodeDAO
  {
    const TABLE_NAME = 'moxim_modules';

    public function __construct($ds)
    {
      $this->columns["name"] = PDO::PARAM_STR;
      parent::__construct($ds);
    }
  }

?>
