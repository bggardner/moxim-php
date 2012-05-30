<?php

  namespace MoXIM\services\dataproviders;

  use MoXIM\models\Assignment;
  use MoXIM\models\Module;
  use MoXIM\models\Relation;
  use MoXIM\models\Relationship;
  use MoXIM\utils\Exception;


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
        throw new Exception('Gateway for driver "'.htmlspecialchars($driver).'" does not exist.');
      }
      $gatewayClass = __NAMESPACE__ . '\\gateways\\' . str_replace('/', '\\', $driver) . '\\BaseGateway';
      $this->gateway = new $gatewayClass($ds);

      $daos = array('Assignment', 'Module', 'Relation', 'Relationship');
      foreach ($daos as $dao)
      {
        if ((include_once __DIR__ . '/daos/' . $driver . '/' . $dao . 'DAO.php') === FALSE)
        {
          throw new Exception($dao . ' DAO for driver "'.htmlspecialchars($driver).'" does not exist.');
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
        throw new Exception('DAO for class "'.$class.'" not supported.');
      }
      if (!method_exists($this->daos[$class], $name))
      {
        throw new Exception($class.'DAO method "'.$name.'" not supported.');
      }
      try {
        return $this->daos[$class]->$name($args[0]);
      } catch (\Exception $e)
      {
        throw new Exception('DAO method "'.$name.'('.$class.')" failed with message "'.$e->getMessage(), Exception::DP, $e);
      }
    }

    /* Gateway functions */

    public function getAssignments($module, $node, $value, $opts)
    {
      try {
        return $this->gateway->getAssignments($module, $node, $value, $opts);
      } catch (\Exception $e)
        $this->gatewayException(__FUNCTION__, $e);
      }
    }

    public function getModules($opts)
    {
      try {
        return $this->gateway->getModules($opts);
      } catch (\Exception $e)
        $this->gatewayException(__FUNCTION__, $e);
      }
    }

    public function getNodes(Module $module, $opts)
    {
      try {
        return $this->gateway->getNodes($module, $opts);
      } catch (\Exception $e)
        $this->gatewayException(__FUNCTION__, $e);
      }
    }

    public function getRelations($source, $name, $target, $opts)
    {
      try {
        return $this->gateway->getRelations($source, $name, $target, $opts);
      } catch (\Exception $e)
        $this->gatewayException(__FUNCTION__, $e);
      }
    }

    public function getRelationships($source, $relation, $target, $opts)
    {
      try {
        return $this->gateway->getRelationships($range, $relation, $target, $opts);
      } catch (\Exception $e)
        $this->gatewayException(__FUNCTION__, $e);
      }
    }

    public function moduleExists($name)
    {
      try {
        return $this->gateway->moduleExists($name);
      } catch (\Exception $e)
        $this->gatewayException(__FUNCTION__, $e);
      }
    }

    public function nodeExists(Module $module, $node)
    {
      try {
        return $this->gateway->nodeExists($module, $node);
      } catch (\Exception $e)
        $this->gatewayException(__FUNCTION__, $e);
      }
    }

    /* Helper methods */

    private function gatewayException($f, $e)
    {
      throw new Exception('Gateway method "'.$f.'" failed with message "'.$e->getMessage().'", Exception::DP, $e);
    }
  }
?>
