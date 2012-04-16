<?php
  namespace ModXIM;
  
  abstract class PDOService
  {
    protected $pdo;
  
    public function __construct()
    {
      $this->pdo = $this->_connect('moxim'); // DB name should be set in config file
      
      // Turn off database native prepares since most queries are unique
      $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, TRUE);
    }
  
    abstract protected function addModule($name);
    abstract protected function addRelation($name, $domain, $range);
    abstract private function _connect($dbname);
    abstract protected function deleteModule($id);
    abstract protected function deleteRelation($id);
    abstract protected function idExists($table, $id);
  }
?>