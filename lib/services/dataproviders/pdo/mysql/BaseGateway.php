<?php
  namespace MoXIM\services\dataproviders\pdo\mysql;
  use MoXIM\models\Assignment;
  use MoXIM\models\Module;
  use MoXIM\models\Relation;
  use MoXIM\models\Relationship;

  require_once realpath(__DIR__ . '/../../../../../../classes/MySQLPDO.php'); // Delete later
  
  class BaseGateway extends \MoXIM\services\dataproviders\pdo\BaseGateway
  {    
    public function __construct($o)
    {
      // Add some error checking here
      // Connect to database using PDO
      //parent::__construct('mysql:host='.$o->host.';username='.$o->username.';password='.$o->password.';dbname='.$o->dbname);
      $this->dp = new \eBrent\MySQLPDO($o->dbname); // Delete later
    }
    
    public function addAssignment(Assignment $a)
    {

    }

    public function addModule(Module $m)
    {
      $sql = 'INSERT INTO `'.self::MODULES.'` (name) VALUES ('.$m->name.')';
      $this->dp->exec($sql);
      return (int) $this->dp->lastInsertId();
    }
    
    public function addRelation(Relation $r)
    {
      $sql = 'INSERT INTO `'.self::RELATIONS.'` (`domain`, `name`, `range`) VALUES ('.$r->domain.', "'.$r->name.'", '.$r->range.')';
      $this->dp->exec($sql);
      return (int) $this->dp->lastInsertId();
    }
    
    public function addRelationship(Relationship $r)
    {
      $sql = 'INSERT INTO `'.self::RELATIONSHIPS.'` (`domain`, `name`, `range`) VALUES ('.$r->domain.', '.$r->relation.', '.$r->range.')';
      $this->dp->exec($sql);
      return (int) $this->dp->lastInsertId();
    }
    
    public function deleteAssignment($id)
    {
      $sql = 'DELETE FROM `'.self::ASSIGNMENTS.'` WHERE `id` = '.$id;
      return (bool) $this->dp->exec($sql);
    }
    
    public function deleteModule($id)
    {
      $sql = 'DELETE FROM `'.self::MODULES.'` WHERE `id` = '.$id;
      return (bool) $this->dp->exec($sql);
    }
    
    public function deleteRelation($id)
    {
      $sql = 'DELETE FROM `'.self::RELATIONS.'`WHERE `id` = '.$id;
      return (bool) $this->dp->exec($sql);
    }
    
    public function deleteRelationship($id)
    {
      $sql = 'DELETE FROM `'.self::RELATIONSHIPS.'`WHERE `id` = '.$id;
      return (bool) $this->dp->exec($sql);
    }

    public function getAssignment($id)
    {

    }
    
    public function getAssignmentId($module, $node, $value)
    {

    }
    
    public function getModule($id)
    {

    }
    
    public function getModuleId($name)
    {

    }
    
    public function getRelation($id)
    {

    }
    
    public function getRelationId($domain, $name, $range)
    {

    }
    
    public function getRelationship($id)
    {

    }
    
    public function getRelationshipId($domain, $relation, $range)
    {

    }

    public function updateAssignment(Assignment $a)
    {

    }

    public function updateModule(Module $m)
    {
      $sql = 'UPDATE `'.self::MODULES.'` SET `name` = "'.$m->name.'" WHERE `id` = '.$m->id;
      if ($this->dp->exec($sql))
      {
        $m->id = $this->dp->lastInsertId();
        return $m;
      }
      return FALSE;
    }

    public function updateRelation(Relation $r)
    {
      $sql = 'UPDATE `'.self::RELATIONS.'` SET `domain` = '.$r->domain.', `name` = "'.$r->name.'", `range` = '.$r->range;
      if ($this->dp->exec($sql))
      {
        $m->id = $this->dp->lastInsertId();
        return $m;
      }
      return FALSE;
    }

    public function updateRelationship(Relationship $r)
    {

    }
  }
  
?>
