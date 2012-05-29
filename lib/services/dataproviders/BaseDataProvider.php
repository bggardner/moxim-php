<?php

  namespace MoXIM\services\dataproviders;

  use MoXIM\models\Assignment;
  use MoXIM\models\Module;
  use MoXIM\models\Relation;
  use MoXIM\models\Relationship;

  use RuntimeException;

  abstract class BaseDataProvider
  {
    public $ds; // Possibly remove later

    protected $gateway;
    protected $daos = array();

    public function __construct($ds, $driver)
    {
      $this->ds = $ds; // Possibly remove later
      if ((include_once __DIR__ . '/gateways/' . $driver . '/BaseGateway.php') === FALSE)
      {
        throw new RuntimeException('Gateway for driver "'.htmlspecialchars($driver).'" does not exist.');
      }
      $gatewayClass = __NAMESPACE__ . '\\gateways\\' . str_replace('/', '\\', $driver) . '\\BaseGateway';
      $this->gateway = new $gatewayClass($ds);

      $daos = array('Assignment', 'Module', 'Relation', 'Relationship');
      foreach ($daos as $dao)
      {
        if ((include_once __DIR__ . '/daos/' . $driver . '/' . $dao . 'DAO.php') === FALSE)
        {
          throw new RuntimeException($dao . ' DAO for driver "'.htmlspecialchars($driver).'" does not exist.');
        }
        $daoClass = __NAMESPACE__ . '\\daos\\' . str_replace('/', '\\', $driver) . '\\' . $dao . 'DAO';
        $this->daos[$dao] = new $daoClass($ds);
      }
    }

    /* DAO functions */

    public function __call($name, $args)
    {
      $class = basename(str_replace('\\', '/', get_class($args[0])));
      if (in_array($class, array_keys($this->daos)) === FALSE)
      {
        throw new RuntimeException('DAO for class "'.htmlspecialchars($class).'" not supported.');
      }
      if (!method_exists($this->daos[$class], $name))
      {
        throw new RuntimeException($class.'DAO method "'.htmlspecialchars($name).'" not supported.');
      }
      return $this->daos[$class]->$name($args[0]);
    }

    /* Gateway functions */

    public function getAssignments($module, $node, $value, $opts)
    {
      return $this->gateway->getAssignments($module, $node, $value, $opts);
    }

    public function getModules($opts)
    {
      return $this->gateway->getModules($opts);
    }

    public function getNodes($module, $opts)
    {
      $m = new Module();
      $m->id = $module;
      return $this->gateway->getNodes($this->get($m)->name, $opts);
    }

    public function getRelations($source, $name, $target, $opts)
    {
      return $this->gateway->getRelations($source, $name, $target, $opts);
    }

    public function getRelationships($source, $relation, $target, $opts)
    {
      return $this->gateway->getRelationships($range, $relation, $target, $opts);
    }

    public function moduleExists($name)
    {
      return $this->gateway->moduleExists($name);
    }

    public function nodeExists(Module $module, $node)
    {
      return $this->gateway->nodeExists($module, $node);
    }
  }
?>
