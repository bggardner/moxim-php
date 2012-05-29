<?php

  namespace MoXIM\services\dataproviders\gateways\pdo;

  use MoXIM\models\Assignment;
  use MoXIM\models\Module;
  use MoXIM\models\Relation;
  use MoXIM\models\Relationship;

  use PDO;

  require_once realpath(__DIR__ . '/../../../../../../classes/MySQLPDO.php'); // Delete later

  abstract class BaseGateway implements \MoXIM\services\dataproviders\gateways\IBaseGateway
  {
    // Table names
    const ASSIGNMENTS = 'moxim_assignments';
    const MODULES = 'moxim_modules';
    const RELATIONS = 'moxim_relations';
    const RELATIONSHIPS = 'moxim_relationships';

    protected $ds;

    public function __construct($ds)
    {
      $this->ds = $ds;
    }

    public function getAssignments($module, $node, $value, $opts)
    {
      if (!is_null($module))
      {
        $module = $this->ds->quote($module, PDO::PARAM_INT);
      }
      if (!is_null($node))
      {
        $node = $this->ds->quote($node);
      }
      if (!is_null($value))
      {
        $value = $this->ds->quote($value);
      }
      $stmt = $this->ds->query(static::_getAssignments($module, $node, $value, $opts));
      return $stmt->fetchAll(PDO::FETCH_CLASS, 'MoXIM\models\Assignment');
    }

    public function getModuleId($name)
    {
      $stmt = $this->ds->query(static::_getModuleId($this->ds->quote($name)));
      return $stmt->fetchColumn();
    }

    public function getModules($opts)
    {
      $stmt = $this->ds->query(static::_getModules($opts));
      return $stmt->fetchAll(PDO::FETCH_CLASS, 'MoXIM\models\Module');
    }

    public function getNodes($module, $opts)
    {
      $stmt = $this->ds->query(static::_getNodes($module, $opts));
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRelationId($source, $name, $target)
    {
      $stmt = $this->ds->query(static::_getRelationId($this->ds->quote($source, PDO::PARAM_INT), $this->ds->quote($name), $this->ds->quote($target, PDO::PARAM_INT)));
      return $stmt->fetchColumn();
    }

    public function getRelations($source, $name, $target, $opts)
    {
      if (!is_null($source))
      {
        $source = $this->ds->quote($source);
      }
      if (!is_null($name))
      {
        $name = $this->ds->quote($name);
      }
      if (!is_null($target))
      {
        $target = $this->ds->quote($target);
      }
      $stmt = $this->ds->query(static::_getRelations($source, $name, $target, $opts));
      return $stmt->fetchAll(PDO::FETCH_CLASS, 'MoXIM\models\Relation');
    }

    public function getRelationshipId($source, $relation, $target)
    {
      $stmt = $this->ds->query(static::_getRelationshipId($this->ds->quote($source, PDO::PARAM_INT), $this->ds->quote($relation, PDO::PARAM_INT), $this->ds->quote($target, PDO::PARAM_INT)));
      return $stmt->fetchColumn();
    }

    public function getRelationships($source, $relation, $target, $opts)
    {
      if (!is_null($source))
      {
        $source = $this->ds->quote($source);
      }
      if (!is_null($relation))
      {
        $relation = $this->ds->quote($relation);
      }
      if (!is_null($target))
      {
        $target = $this->ds->quote($target);
      }
      $stmt = $this->ds->query(static::_getRelationships($source, $relation, $target, $opts));
      return $stmt->fetchAll(PDO::FETCH_CLASS, 'MoXIM\models\Relationship');
    }

    public function moduleExists($name)
    {
      $name = $this->ds->quote($name);
      $stmt = $this->ds->query(static::_moduleExists($name));
      return (bool) $stmt->rowCount();
    }

    public function nodeExists(Module $module, $node)
    {
      $node = $this->ds->quote($node, PDO::PARAM_INT);
      $stmt = $this->ds->query(static::_nodeExists($module, $node));
      return (bool) $stmt->rowCount();
    }
}

?>
