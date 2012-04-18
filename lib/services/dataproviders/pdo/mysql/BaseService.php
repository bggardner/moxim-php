<?php
  namespace MoXIM\services\dataproviders\pdo\mysql;
  use MoXIM\models\Module;
  use MoXIM\models\Relation;

  require_once realpath(__DIR__ . '/../../../../../../classes/MySQLPDO.php');
  
  abstract class BaseService extends \MoXIM\services\dataproviders\AbstractBaseService
  {    
    public function __construct($o)
    {
      // Add some error checking here
      // Connect to database using PDO
      //return new \PDO('mysql:host='.$o->host.';username='.$o->username.';password='.$o->password.';dbname='.$o->dbname);
      $this->dp = new \eBrent\MySQLPDO($o->dbname); // Delete later
    }
    
    protected function addModule(Module $m)
    {
      $sql = 'INSERT INTO `'.self::MODULES.'` (name) VALUES ('.$m->name.')';
      $this->dp->exec($sql);
      return (int) $this->dp->lastInsertId();
    }
    
    protected function addRelation(Relation $r)
    {
      $sql = 'INSERT INTO `'.self::RELATIONS.'` (`domain`, `name`, `range`) VALUES ('.$r->domain.', "'.$r->name.'", '.$r->range.')';
      $this->dp->exec($sql);
      return (int) $this->dp->lastInsertId();
    }
    
    protected function deleteModule($id)
    {
      $sql = 'DELETE FROM `'.self::MODULES.'` WHERE id = '.$id;
      return (bool) $this->dp->exec($sql);
    }
    
    protected function deleteRelation($id)
    {
      $sql = 'DELETE FROM `'.self::RELATIONS.'`WHERE id = '.$id;
      return (bool) $this->dp->exec($sql);
    }
    
    protected function idExists($table, $id)
    {
      $sql = 'SELECT 1 FROM `'.$table.'` WHERE id = '.$id;
      return (bool) $this->dp->exec($sql);
    }

    protected function updateModule(Module $m)
    {
      $sql = 'UPDATE `'.self::MODULES.'` SET `name` = "'.$m->name.'" WHERE `id` = '.$m->id;
      if ($this->dp->exec($sql))
      {
        $m->id = $this->dp->lastInsertId();
        return $m;
      }
      return FALSE;
    }

    protected function updateRelation(Relation $r)
    {
      $sql = 'UPDATE `'.self::RELATIONS.'` SET `domain` = '.$r->domain.', `name` = "'.$r->name.'", `range` = '.$r->range;
      if ($this->dp->exec($sql))
      {
        $m->id = $this->dp->lastInsertId();
        return $m;
      }
      return FALSE;
    }
  }
  
?>
