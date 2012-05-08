<?php
  namespace MoXIM\services\dataproviders\pdo\mysql;
  use MoXIM\models\Assignment;
  use MoXIM\models\Module;
  use MoXIM\models\Relation;
  use MoXIM\models\Relationship;

  class BaseGateway extends \MoXIM\services\dataproviders\pdo\BaseGateway
  {
    private static $_key; // Used for nodeExists
    private static $_module; // Used for nodeExists

    public function __construct($o)
    {
      // Add some error checking here
      // Connect to database using PDO
      parent::__construct('mysql:host='.$o->host.';username='.$o->username.';password='.$o->password.';dbname='.$o->dbname);
    }

    static public function _addAssignment(Assignment $a)
    {
      return 'INSERT INTO `'.self::ASSIGNMENTS.'` (`module`, `node`, `value`) VALUES ('.$a->module.', '.$a->node.', '.$a->value.')';
    }

    static public function _addModule(Module $m)
    {
      return 'INSERT INTO `'.self::MODULES.'` (name) VALUES ('.$m->name.')';
    }

    static public function _addRelation(Relation $r)
    {
      return 'INSERT INTO `'.self::RELATIONS.'` (`domain`, `name`, `range`) VALUES ('.$r->domain.', '.$r->name.', '.$r->range.')';
    }

    static public function _addRelationship(Relationship $r)
    {
      return 'INSERT INTO `'.self::RELATIONSHIPS.'` (`domain`, `relation`, `range`) VALUES ('.$r->domain.', '.$r->relation.', '.$r->range.')';
    }

    static public function _deleteAssignment($id)
    {
      return 'DELETE FROM `'.self::ASSIGNMENTS.'` WHERE `id` = '.$id;
    }

    static public function _deleteModule($id)
    {
      return 'DELETE FROM `'.self::MODULES.'` WHERE `id` = '.$id;
    }

    static public function _deleteRelation($id)
    {
      return 'DELETE FROM `'.self::RELATIONS.'`WHERE `id` = '.$id;
    }

    static public function _deleteRelationship($id)
    {
      return 'DELETE FROM `'.self::RELATIONSHIPS.'`WHERE `id` = '.$id;
    }

    static public function _getAssignment($id)
    {
      return 'SELECT * FROM `'.self::ASSIGNMENTS.'` WHERE `id` = '.$id;
    }

    static public function _getAssignmentId($module, $node, $value)
    {
      return 'SELECT `id` FROM `'.self::ASSIGNMENTS.'` WHERE `module` = '.$module.' AND `node` = '.$node.' AND `value` = '.$value;
    }

    static public function _getAssignments($module, $node, $value, $opts)
    {
      $sql = 'SELECT * FROM `'.self::ASSIGNMENTS.'` WHERE 1';
      if (!is_null($module))
      {
        $sql .= ' AND `module` = '.$module;
      }
      if (!is_null($node))
      {
        $sql .= ' AND `node` = '.$node;
      }
      if (!is_null($value))
      {
        $sql .= ' AND `value` = '.$value;
      }
      return $sql.self::_options($opts);
    }

    static public function _getModule($id)
    {
      return 'SELECT * FROM `'.self::MODULES.'` WHERE `id` = '.$id;
    }

    static public function _getModuleId($name)
    {
      return 'SELECT `id` FROM `'.self::MODULES.'` WHERE `name` = '.$name;
    }

    static public function _getModules($opts)
    {
      return 'SELECT * FROM `'.self::MODULES.'`'.self::_options($opts);
    }

    static public function _getRelation($id)
    {
      return 'SELECT * FROM `'.self::RELATIONS.'` WHERE `id` = '.$id;
    }

    static public function _getRelationId($domain, $name, $range)
    {
      return 'SELECT `id` FROM `'.self::RELATIONS.'` WHERE `domain` = '.$domain.' AND `name` = '.$name.' AND `range` = '.$range;
    }

    static public function _getRelations($domain, $name, $range, $opts)
    {
      $sql = 'SELECT * FROM `'.self::RELATIONS.'` WHERE 1';
      if (!is_null($domain))
      {
        $sql .= ' AND `domain` = '.$domain;
      }
      if (!is_null($name))
      {
        $sql .= ' AND `name` = '.$name;
      }
      if (!is_null($range))
      {
        $sql .= ' AND `range` = '.$range;
      }
      return $sql.self::_options($opts);
    }

    static public function _getRelationship($id)
    {
      return 'SELECT * FROM `'.self::RELATIONSHIPS.'` WHERE `id` = '.$id;
    }

    static public function _getRelationshipId($domain, $relation, $range)
    {
      return 'SELECT `id` FROM `'.self::RELATIONSHIPS.'` WHERE `domain` = '.$domain.' AND `relation` = '.$relation.' AND `range` = '.$range;
    }

    static public function _getRelationships($domain, $relation, $range, $opts)
    {
      $sql = 'SELECT * FROM `'.self::RELATIONSHIPS.'` WHERE 1';
      if (!is_null($domain))
      {
        $sql .= ' AND `domain` = '.$domain;
      }
      if (!is_null($relation))
      {
        $sql .= ' AND `relation` = '.$relation;
      }
      if (!is_null($range))
      {
        $sql .= ' AND `range` = '.$range;
      }
      return $sql.self::_options($opts);
    }

    static public function _init()
    {
      return file_get_contents(realpath(__DIR__ . '/moxim-php.sql'));
    }

    public function nodeExists($module, $node)
    {
      self::$_module = $this->getModule($module)->name;
      $stmt = $this->dp->query($this->_moduleKey($module));
      self::$_key = $stmt->fetchColumn();
      return parent::nodeExists($m, $node);
    }

    private function _moduleKey($module)
    {
      return '
SELECT `k`.`column_name` FROM `'.self::MODULES.'` AS `m`
JOIN `information_schema`.`table_constraints` AS `t`
ON `m`.`name` = `t`.`table_name`
JOIN `information_schema`.`key_column_usage` AS `k`
USING(`constraint_name`,`table_schema`,`table_name`)
WHERE `t`.`constraint_type` = \'PRIMARY KEY\'
  AND `t`.`table_schema` = SCHEMA()
  AND `m`.`id` = '.$module;
    }

    static public function _nodeExists($module, $node)
    {
      return 'SELECT 1 FROM `'.self::$_module.'` WHERE `'.self::$_key.'` = '.$node;
    }

    static private function _options($opts)
    {
      $sql = '';
      if (isset($opts['sort']))
      {
        $sql .= ' ORDER BY ';
        foreach ($opts['sort'] as $key => $value)
        {
          $order[] = '`'.$key.'` '.(strtoupper($value) == 'DESC' ? 'DESC' : 'ASC');
        }
        $sql .= implode(',', $order);
      }
      if (isset($opts['limit']))
      {
        $sql .= ' LIMIT '.$opts['limit'];
        if (isset($opts['page']))
        {
          $offset = ($opts['page'] - 1) * $opts['limit'];
        }
      }
      if (isset($opts['offset']))
      {
        $offset = $opts['offset'];
      }
      if (isset($offset))
      {
        $sql .= ' OFFSET '.$offset;
      }
      return $sql;
    }

    static public function _updateAssignment(Assignment $a)
    {
      return 'UPDATE `'.self::ASSIGNMENTS.'` SET `module` = '.$a->module.', `node` = '.$a->node.', `value` = '.$a->value.' WHERE `id` = '.$a->id;
    }

    static public function _updateModule(Module $m)
    {
      return 'UPDATE `'.self::MODULES.'` SET `name` = '.$m->name.' WHERE `id` = '.$m->id;
    }

    static public function _updateRelation(Relation $r)
    {
      return 'UPDATE `'.self::RELATIONS.'` SET `domain` = '.$r->domain.', `name` = '.$r->name.', `range` = '.$r->range.' WHERE `id` = '.$r->id;
    }

    static public function _updateRelationship(Relationship $r)
    {
      return 'UPDATE `'.self::RELATIONSHIPS.'` SET `domain` = '.$r->domain.', `relation` = '.$r->relation.', `range` = '.$r->range.' WHERE `id` = '.$r->id;
    }
  }
?>
