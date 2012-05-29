<?php

  namespace MoXIM\services\dataproviders\daos\pdo\mysql;

  use MoXIM\models\Node;

  class NodeDAO
  {
    static public function add($table, Node $n)
    {
      $props = self::props($n);
      return 'INSERT INTO `'.$table.'` (`'.implode('`,`', $props).'`) VALUES (:'.implode(',:', $props).')';
    }

    static public function delete($table, Node $n)
    {
      return 'DELETE FROM `'.$table.'` WHERE '.self::filter($n);
    }

    static public function get($table, Node $n)
    {
      return 'SELECT * FROM `'.$table.'` WHERE '.self::filter($n);
    }

    static public function update($table, Node $n)
    {
      return 'UPDATE `'.$table.'` SET '.implode(',', self::buildProps($n)).' WHERE `id` = :id';
    }

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
