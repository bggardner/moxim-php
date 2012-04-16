<?php
  namespace MoXIM;
  
  // Look into replacing with autoloading
  require_once 'Module.php';
  require_once 'Relation.php';
  
  use \RuntimeException;
  
  /* This class provides error checking to the extended service. */
  
  class BaseService extends MySQLService // Change to desired database service
  {
    const MODULES = 'modules';
    const RELATIONS = 'relations';
    
    public function addModule($name)
    {
      if (is_null($name))
      {
        throw new RuntimeException('New module name is required.');
      }
      if (strlen($name) > Module::NAME_LENGTH)
      {
        throw new RuntimeException('New module name must be less than '.Module::NAME_LENGTH.' characters.');
      }
      return parent::addModule($name);
    }
    
    public function addRelation($domain, $name, $range)
    {
      if (is_null($domain))
      {
        throw new RuntimeException('New relation domain is required.');
      }
      if (is_null($name))
      {
        throw new RuntimeException('New relation name is required.');
      }
      if (is_null($range))
      {
        throw new RuntimeException('New relation range is required.');
      }
      if (!is_int($domain) || $domain < 1)
      {
        throw new RuntimeException('New relation domain must be a positive integer, '.gettype($domain).'('.htmlspecialchars($domain).') given.');
      }
      if (strlen($name) > Relation::NAME_LENGTH)
      {
        throw new RuntimeException('New relation name must be less than '.Relation::NAME_LENGTH.' characters.');
      }
      if (!is_int($range) || $range < 1)
      {
        throw new RuntimeException('New relation range must be a positive integer, '.gettype($range).'('.htmlspecialchars($range).') given.');
      }
      if (!parent::idExists(self::MODULES, $domain))
      {
        throw new RuntimeException('Domain module id "'.htmlspecialchars($domain).'" does not exist.');
      }
      if (!parent::idExists(self::MODULES, $range))
      {
        throw new RuntimeException('Range module id "'.htmlspecialchars($domain).'" does not exist.');
      }
      return parent::addRelation($domain, $this->pdo->quote($name), $range);
    }
  }
  
?>