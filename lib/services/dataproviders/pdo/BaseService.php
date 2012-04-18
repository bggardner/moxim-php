<?php
  namespace MoXIM\services\dataproviders\pdo;
  
  abstract class BaseService extends mysql\BaseService
  {  
    public function __construct($o)
    {
      parent::__construct($o );
      
      // Turn off database native prepares since most queries are unique
      $this->dp->setAttribute(\PDO::ATTR_EMULATE_PREPARES, TRUE);
    }
  }
?>
