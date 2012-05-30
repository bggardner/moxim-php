<?php

  namespace MoXIM\services\dataproviders\gateways\pdo\mysql;

  use MoXIM\models\Assignment;
  use MoXIM\models\Module;
  use MoXIM\models\Relation;
  use MoXIM\models\Relationship;

  class BaseGateway extends \MoXIM\services\dataproviders\gateways\pdo\BaseGateway
  {
    static public function _getAssignments($module, $node, $value, $opts)
    {
      $sql = 'SELECT * FROM `'.Assignment::MODULE_NAME.'` WHERE 1';
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

    static public function _getModules($opts)
    {
      return 'SELECT * FROM `'.Module::MODULE_NAME.'`'.self::_options($opts);
    }

    static public function _getNodes(Module $module, $opts)
    {
      return 'SELECT * FROM `'.$module->name.'`'.self::_options($opts);
    }

    static public function _getRelations($source, $name, $target, $opts)
    {
      $sql = 'SELECT * FROM `'.Relation::MODULE_NAME.'` WHERE 1';
      if (!is_null($source))
      {
        $sql .= ' AND `source` = '.$source;
      }
      if (!is_null($name))
      {
        $sql .= ' AND `name` = '.$name;
      }
      if (!is_null($target))
      {
        $sql .= ' AND `target` = '.$target;
      }
      return $sql.self::_options($opts);
    }

    static public function _getRelationships($source, $relation, $target, $opts)
    {
      $sql = 'SELECT * FROM `'.Relationship::MODULE_NAME.'` WHERE 1';
      if (!is_null($source))
      {
        $sql .= ' AND `source` = '.$source;
      }
      if (!is_null($relation))
      {
        $sql .= ' AND `relation` = '.$relation;
      }
      if (!is_null($target))
      {
        $sql .= ' AND `target` = '.$target;
      }
      return $sql.self::_options($opts);
    }

    static public function _moduleExists($name)
    {
      return 'SHOW TABLES LIKE '.$name;
    }

    static public function _nodeExists(Module $module, $node)
    {
      return 'SELECT 1 FROM `'.$module->name.'` WHERE `id` = '.$node;
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
  }

?>
