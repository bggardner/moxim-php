<?php
  namespace MoXIM\services\dataproviders\pdo;
  use \MoXIM\services\dataproviders\IBaseGateway;
  
  abstract class BaseGateway implements IBaseGateway
  {
    public $dp;

    public function __construct($dsn)
    {
      $this->dp = new \PDO($dsn);
      // Turn off database native prepares since most queries are unique
      $this->dp->setAttribute(\PDO::ATTR_EMULATE_PREPARES, TRUE);
    }
  }
?>
