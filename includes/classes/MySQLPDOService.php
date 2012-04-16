<?php
  namespace MoXIM;
  
  include '../../../classes/MySQLPDO.php';
  
  /* This class is extended by BaseService, which performs error checking and injection prevention. */
  
  abstract class MySQLPDOService extends PDOService
  {    
    private function _connect($dbname)
    {
      // Connect to database using PDO
      //return new PDO('mysql:host='.$host.';username='.$username.';password='.$password.';dbname='.$dbname);
      return new \eBrent\MySQLPDO($dbname);
    }
    
    protected function addModule($name)
    {
      $sql = 'INSERT INTO `'.self::MODULES.'` (name) VALUES ('.$name.')';
      $this->pdo->exec($sql);
      return (int) $this->pdo->lastInsertId();
    }
    
    protected function addRelation($domain, $name, $range)
    {
      $sql = 'INSERT INTO `'.self::RELATIONS.'` (`domain`, `name`, `range`) VALUES ('.$id.', "'.$name.'", '.$range.')';
      $this->pdo->exec($sql);
      return (int) $this->pdo->lastInsertId();
    }
    
    protected function deleteModule($id)
    {
      $sql = 'DELETE FROM `'.self::MODULES.'` WHERE id = '.$id;
      return (bool) $this->pdo->exec($sql);
    }
    
    protected function deleteRelation($id)
    {
      $sql = 'DELETE FROM `'.self::RELATIONS.'`WHERE id = '.$id;
      return (bool) $this->pdo->exec($sql);
    }
    
    protected function idExists($table, $id)
    {
      $sql = 'SELECT 1 FROM `'.$table.'` WHERE id = '.$id;
      return (bool) $this->pdo->exec($sql);
    }
  }
  
?>