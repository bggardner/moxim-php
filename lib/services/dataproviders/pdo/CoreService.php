<?php
  namespace MoXIM\services\dataproviders\pdo;
  
  require_once 'mysql/CoreService.php';
  
  abstract class CoreService extends mysql\CoreService
  {  
    public function __construct($o)
    {
      parent::__construct($o );
      
      // Turn off database native prepares since most queries are unique
      $this->db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, TRUE);
    }
  }
?>
