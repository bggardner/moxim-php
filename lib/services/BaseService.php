<?php
  namespace MoXIM\services;
  use MoXIM\models\Assignment;
  use MoXIM\models\Module;
  use MoXIM\models\Relation;
  use MoXIM\models\Relationship;
  
  use \RuntimeException;
  
  /* This class provides error checking to gateway/dataprovider. */
  
  class BaseService
  {
    const ASSIGNMENTS = 'moxim_assignments';
    const MODULES = 'moxim_modules';
    const RELATIONS = 'moxim_relations';
    const RELATIONSHIPS = 'moxim_relationships';

    public $dp;
    private $gateway;

    public function __construct($o)
    {
      if (!(include_once 'dataproviders/' . $o->driver . '/BaseGateway.php'))
      {
        throw new Exception('Driver "'.htmlspecialchars($o->driver).'" does not exist.');
      }
      $driverClass = __NAMESPACE__ . '\\dataproviders\\' . str_replace('/', '\\', $o->driver) . '\\BaseGateway';
      $this->gateway = new $driverClass($o);
      if (!($this->gateway instanceof dataproviders\IBaseGateway))
      {
        throw new RuntimeException('Invalid gateway "'.htmlspecialchars($o->driver).'".  Must implement IBaseGateway.');
      }
      $this->dp = $this->gateway->dp; // Make dataprovider publicly accessible
    }

    public function addAssignment(Assignment $a)
    {
      $a->validate();
      if ($this->idExists($a->module, $a->node) === FALSE)
      {
        throw new RuntimeException('Assignment node id "'.htmlspecialchars($a->node).'" does not exist in module "'.htmlspecialchars($this->getModule($a->module)->name).'".');
      }
      return parent::addAssignment($a);
    }
    
    public function addModule(Module $m)
    {
      $m->validate();
      return parent::addModule($m);
    }
    
    public function addRelation(Relation $r)
    {
      $r->validate();
      if (!parent::getModule($r->domain))
      {
        throw new RuntimeException('Domain module id "'.htmlspecialchars($r->domain).'" does not exist.');
      }
      if (!parent::getModule($r->range))
      {
        throw new RuntimeException('Range module id "'.htmlspecialchars($r->range).'" does not exist.');
      }
      return parent::addRelation($r);
    }

    public function addRelationship(Relationship $r)
    {
      $r->validate();
      if (($relation = parent::getRelation($r->relation)) === FALSE)
      {
        throw new RuntimeException('Relationship relation id "'.$r->relation.'" does not exist.');
      }
      if (!$this->idExists($relation->domain, $r->domain))
      {
        throw new RuntimeException('Relationship domain id "'.htmlspecialchars($r->domain).'" does not exist in module "'.htmlspecialchars(getModule($relation->domain)->name).'".');
      }
      if (!$this->idExists($relation->range, $r->range))
      {
        throw new RuntimeException('Relationship range id "'.htmlspecialchars($r->range).'" does not exist in module "'.htmlspecialchars(getModule($relation->range)->name).'".');
      }
    }
    
    public function deleteAssignment($id)
    {
      $id = Assignment::validateId($id);
      if (!parent::deleteAssignment($id))
      {
        throw new RuntimeException('Assignment id "'.htmlspecialchars($id).'" does not exist.');
      }
    }
    
    public function deleteModule($id)
    {
      $id = Module::validateId($id);
      if (!parent::deleteModule($id))
      {
        throw new RuntimeException('Module id "'.htmlspecialchars($id).'" does not exist.');
      }
    }
    
    public function deleteRelation($id)
    {
      $id = Relation::validateId($id);
      if (!parent::deleteRelation($id))
      {
        throw new RuntimeException('Relation id "'.htmlspecialchars($id).'" does not exist.');
      }
    }
  }
  
?>
