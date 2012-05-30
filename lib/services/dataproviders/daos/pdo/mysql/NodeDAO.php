<?php

  namespace MoXIM\services\dataproviders\daos\pdo\mysql;

  use MoXIM\models\Node;

  class NodeDAO implements \MoXIM\services\dataproviders\daos\pdo\INodeDAO
  {
    static public function add(Node $n)
    {
      $class = get_class($n);
      $props = self::props($n);
      return 'INSERT INTO `'.$class::MODULE_NAME.'` (`'.implode('`,`', $props).'`) VALUES (:'.implode(',:', $props).')';
    }

    static public function delete(Node $n)
    {
      $class = get_class($n);
      return 'DELETE FROM `'.$class::MODULE_NAME.'` WHERE '.self::filter($n).' LIMIT 1';
    }

    static public function get(Node $n)
    {
      $class = get_class($n);
      return 'SELECT * FROM `'.$class::MODULE_NAME.'` WHERE '.self::filter($n);
    }

    static public function update(Node $n)
    {
      $class = get_class($n);
      return 'UPDATE `'.$class::MODULE_NAME.'` SET '.implode(',', self::buildProps($n)).' WHERE `id` = :id';
    }

    /* Helper methods*/

    static private function buildProps($n)
    {
      $set = array();
      foreach (self::props($n) as $prop)
      {
        $set[] = '`'.$prop.'` = :'.$prop;
      }
      return $set;
    }

    static private function props(Node $n)
    {
      return array_keys(array_diff(get_object_vars($n),array(NULL)));
    }

    static private function filter(Node $n)
    {
      $filter = implode(' AND ', self::buildProps($n));
      return $filter;
    }

  }
?>
