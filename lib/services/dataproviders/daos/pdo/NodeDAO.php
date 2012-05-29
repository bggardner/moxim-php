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
      $class = get_class($n);
      $driver = $this->driver;
      $stmt = $this->ds->prepare($driver::add(static::TABLE_NAME, $n));
      $this->bindValues($stmt, $n);
      $stmt->execute();
      $n->id = (int) $this->ds->lastInsertId('id');
      return $n;
    }

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

    public function delete(Node $n)
    {
      $class = get_class($n);
      $driver = $this->driver;
      $stmt = $this->ds->prepare($driver::delete(self::TABLE_NAME, $n));
      $this->bindValues($stmt, $n);
      return (bool) $stmt->execute();
    }

    public function get(Node $n)
    {
      $class = get_class($n);
      $driver = $this->driver;
      $stmt = $this->ds->prepare($driver::get(static::TABLE_NAME, $n));
      $this->bindValues($stmt, $n);
      $stmt->execute();
      $this->bindColumns($stmt, $n);
      $stmt->fetch(PDO::FETCH_BOUND);
      return $n;
    }

    public function update(Node $n)
    {
      $class = get_class($n);
      $driver = $this->driver;
      $stmt = $this->ds->prepare($driver::update(static::TABLE_NAME, $n));
      $this->bindValues($stmt, $n);
      return $stmt->execute();
    }
  }
?>
