<?php
  namespace MoXIM\services\dataproviders\pdo;
  use PDO;
  use MoXIM\models\Assignment;
  use MoXIM\models\Module;
  use MoXIM\models\Relation;
  use MoXIM\models\Relationship;

  require_once realpath(__DIR__ . '/../../../../../classes/MySQLPDO.php'); // Delete later

  abstract class BaseGateway implements \MoXIM\services\dataproviders\IBaseGateway
  {
    // Table names
    const ASSIGNMENTS = 'moxim_assignments';
    const MODULES = 'moxim_modules';
    const RELATIONS = 'moxim_relations';
    const RELATIONSHIPS = 'moxim_relationships';

    public $dp;

    public function __construct($dsn)
    {
      //$this->dp = new PDO($dsn); // Uncomment later
      $this->dp = new \eBrent\MySQLPDO('moxim'); // Delete later

      // Turn off database native prepares since most queries are unique
      $this->dp->setAttribute(PDO::ATTR_EMULATE_PREPARES, TRUE);

      // Initialize database
      $stmt = $this->dp->prepare(static::_init());
      $stmt->execute();
    }

    public function addAssignment(Assignment $a)
    {
      $a2 = clone $a;
      $a2->module = $this->dp->quote($a2->module, PDO::PARAM_INT);
      $a2->node = $this->dp->quote($a2->node, PDO::PARAM_INT);
      $a2->value = $this->dp->quote($a2->value);
      $this->dp->exec(static::_addAssignment($a2));
      $a->id = $this->dp->lastInsertId();
      return $a;
    }

    public function addModule(Module $m)
    {
      $m2 = clone $m;
      $m2->name = $this->dp->quote($m2->name);
      $this->dp->exec(static::_addModule($m2));
      $m->id = $this->dp->lastInsertId();
      return $m;
    }

    public function addRelation(Relation $r)
    {
      $r2 = clone $r;
      $r2->domain = $this->dp->quote($r2->domain, PDO::PARAM_INT);
      $r2->name = $this->dp->quote($r2->name);
      $r2->range = $this->dp->quote($r2->range, PDO::PARAM_INT);
      $this->dp->exec(static::_addRelation($r2));
      $r->id = $this->dp->lastInsertId();
      return $r;
    }

    public function addRelationship(Relationship $r)
    {
      $r2 = clone $r;
      $r2->domain = $this->dp->quote($r2->domain, PDO::PARAM_INT);
      $r2->relation = $this->dp->quote($r2->relation, PDO::PARAM_INT);
      $r2->range = $this->dp->quote($r2->range, PDO::PARAM_INT);
      $this->dp->exec(static::_addRelationship($r2));
      $r->id = $this->dp->lastInsertId();
      return $r;
    }

    public function deleteAssignment($id)
    {
      return (bool) $this->dp->exec(static::_deleteAssignment($this->dp-quote($id, PDO::PARAM_INT)));
    }

    public function deleteModule($id)
    {
      return (bool) $this->dp->exec(static::_deleteModule($this->dp-quote($id, PDO::PARAM_INT)));
    }

    public function deleteRelation($id)
    {
      return (bool) $this->dp->exec(static::_deleteRelation($this->dp->quote($id, PDO::PARAM_INT)));
    }

    public function deleteRelationship($id)
    {
      return (bool) $this->dp->exec(static::_deleteRelationship($this->dp->quote($id, PDO::PARAM_INT)));
    }

    public function getAssignment($id)
    {
      $stmt = $this->dp->query(static::_getAssignment($this->dp->quote($id, PDO::PARAM_INT)));
      return $stmt->fetchObject('MoXIM\models\Assignment');
    }

    public function getAssignments($module, $node, $value, $opts)
    {
      if (!is_null($module))
      {
        $module = $this->dp->quote($module, PDO::PARAM_INT);
      }
      if (!is_null($node))
      {
        $node = $this->dp->quote($node);
      }
      if (!is_null($value))
      {
        $value = $this->dp->quote($value);
      }
      $stmt = $this->dp->query(static::_getAssignments($module, $node, $value, $opts));
      return $stmt->fetchAll(PDO::FETCH_CLASS, 'MoXIM\models\Assignment');
    }

    public function getAssignmentId($module, $node, $value)
    {
      $stmt = $this->dp->query(static::_getAssignmentId($this->dp->quote($module, PDO::PARAM_INT), $this->dp>quote($node, PDO::PARAM_INT), $this->dp->quote($value)));
      return $stmt->fetchColumn();
    }

    public function getModule($id)
    {
      $stmt = $this->dp->query(static::_getModule($this->dp->quote($id, PDO::PARAM_INT)));
      return $stmt->fetchObject('MoXIM\models\Module');
    }

    public function getModuleId($name)
    {
      $stmt = $this->dp->query(static::_getModuleId($this->dp->quote($name)));
      return $stmt->fetchColumn();
    }

    public function getModules($opts)
    {
      $stmt = $this->dp->query(static::_getModules($opts));
      return $stmt->fetchAll(PDO::FETCH_CLASS, 'MoXIM\models\Module');
    }

    public function getNodes($module, $opts)
    {
      $module = $this->dp->quote($module);
      $stmt = $this->dp->query(static::_getNodes($module, $opts));
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRelation($id)
    {
      $stmt = $this->dp->query(static::_getRelation($this->dp->quote($id, PDO::PARAM_INT)));
      return $stmt->fetchObject('MoXIM\models\Relation');
    }

    public function getRelationId($domain, $name, $range)
    {
      $stmt = $this->dp->query(static::_getRelationId($this->dp->quote($domain, PDO::PARAM_INT), $this->dp->quote($name), $this->dp->quote($range, PDO::PARAM_INT)));
      return $stmt->fetchColumn();
    }

    public function getRelations($domain, $name, $range, $opts)
    {
      if (!is_null($domain))
      {
        $domain = $this->dp->quote($domain);
      }
      if (!is_null($name))
      {
        $name = $this->dp->quote($name);
      }
      if (!is_null($range))
      {
        $range = $this->dp->quote($range);
      }
      $stmt = $this->dp->query(static::_getRelations($domain, $name, $range, $opts));
      return $stmt->fetchAll(PDO::FETCH_CLASS, 'MoXIM\models\Relation');
    }

    public function getRelationship($id)
    {
      $stmt = $this->dp->query(static::_getRelationship($this->dp->quote($id, PDO::PARAM_INT)));
      return $stmt->fetchObject('MoXIM\models\Relation');
    }

    public function getRelationshipId($domain, $relation, $range)
    {
      $stmt = $this->dp->query(static::_getRelationshipId($this->dp->quote($domain, PDO::PARAM_INT), $this->dp->quote($relation, PDO::PARAM_INT), $this->dp->quote($range, PDO::PARAM_INT)));
      return $stmt->fetchColumn();
    }

    public function getRelationships($domain, $relation, $range, $opts)
    {
      if (!is_null($domain))
      {
        $domain = $this->dp->quote($domain);
      }
      if (!is_null($relation))
      {
        $relation = $this->dp->quote($relation);
      }
      if (!is_null($range))
      {
        $range = $this->dp->quote($range);
      }
      $stmt = $this->dp->query(static::_getRelationships($domain, $relation, $range, $opts));
      return $stmt->fetchAll(PDO::FETCH_CLASS, 'MoXIM\models\Relationship');
    }

    public function nodeExists($module, $node)
    {
      $module = $this->dp->quote($module, PDO::PARAM_INT);
      $node = $this->dp->quote($node);
      $stmt = $this->dp->query(static::_nodeExists($module, $node));
      return (bool) $stmt->rowCount();
    }

    public function updateAssignment(Assignment $a)
    {
      $a2 = clone $a;
      $a2->id = $this->dp->quote($a2->id, PDO::PARAM_INT);
      $a2->module = $this->dp->quote($a2->module, PDO::PARAM_INT);
      $a2->node = $this->dp->quote($a2->node, PDO::PARAM_INT);
      $a2->value = $this->dp->quote($a2->value);
      return (bool) $this->dp->exec(static::_updateAssignment($a2));
    }

    public function updateModule(Module $m)
    {
      $m2 = clone $m;
      $m2->id = $this->dp->quote($m2->id, PDO::PARAM_INT);
      $m2->name = $this->dp->quote($m2->name);
      return (bool) $this->dp->exec(static::_updateModule($m2));
    }

    public function updateRelation(Relation $r)
    {
      $r2 = clone $r;
      $r2->id = $this->dp->quote($r2->id, PDO::PARAM_INT);
      $r2->domain = $this->dp->quote($r2->domain, PDO::PARAM_INT);
      $r2->name = $this->dp->quote($r2->name);
      $r2->range = $this->dp->quote($r2->range, PDO::PARAM_INT);
      return (bool) $this->dp->exec(static::_updateRelation($r2));
    }

    public function updateRelationship(Relationship $r)
    {
      $r2 = clone $r;
      $r2->id = $this->dp->quote($r2->id, PDO::PARAM_INT);
      $r2->domain = $this->dp->quote($r2->domain, PDO::PARAM_INT);
      $r2->relation = $this->dp->quote($r2->relation, PDO::PARAM_INT);
      $r2->range = $this->dp->quote($r2->range, PDO::PARAM_INT);
      return (bool) $this->dp->exec(static::_updateRelationship($r2));
    }
}
?>
