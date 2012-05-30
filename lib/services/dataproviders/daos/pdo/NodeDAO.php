<?php

  namespace MoXIM\services\dataproviders\daos\pdo;

  use MoXIM\models\Node;

  use PDO;
  use PDOStatement;

  abstract class NodeDAO extends \MoXIM\services\dataproviders\daos\NodeDAO
  {
    protected $columns = array('id' => PDO::PARAM_INT);

    private $driver;

    public function __construct($ds)
    {
      parent::__construct($ds);
      $this->driver = __NAMESPACE__ . '\\' . $this->ds->getAttribute(PDO::ATTR_DRIVER_NAME) . '\\NodeDAO';
    }

    public function add(Node $n)
    {
      $sql = call_user_func($this->driver.'::add', $n);
      $stmt = $this->ds->prepare($sql);
      $this->bindValues($stmt, $n);
      $stmt->execute();
      $n->id = (int) $this->ds->lastInsertId('id');
      return $n;
    }

    public function delete(Node $n)
    {
      $sql = call_user_func($this->driver.'::delete', $n);
      $stmt = $this->ds->prepare($sql);
      $this->bindValues($stmt, $n);
      return $stmt->execute();
    }

    public function get(Node $n)
    {
      $sql = call_user_func($this->driver.'::get', $n);
      $stmt = $this->ds->prepare($sql);
      $this->bindValues($stmt, $n);
      $stmt->execute();
      $this->bindColumns($stmt, $n);
      return $stmt->fetch(PDO::FETCH_BOUND) ? $n : FALSE;
    }

    public function update(Node $n)
    {
      $sql = call_user_func($this->driver.'::update', $n);
      $stmt = $this->ds->prepare($sql);
      $this->bindValues($stmt, $n);
      return $stmt->execute();
    }

    /* Helper methods */

    protected function bindColumns(PDOStatement &$stmt, Node &$n)
    {
      foreach ($this->columns as $col => $type)
      {
        $stmt->bindColumn($col, $n->$col, $type);
      }
    }

    protected function bindValues(PDOStatement &$stmt, Node $n)
    {
      foreach ($this->columns as $col => $type)
      {
        if (isset($n->$col))
        {
          if (!is_null($n->$col))
          {
            $stmt->bindValue($col, $n->$col, $type);
          }
        }
      }
    }

  }

?>
