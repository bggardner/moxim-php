<?php
  namespace MoXIM\services;
  use MoXIM\models\Assignment;
  use MoXIM\models\Module;
  use MoXIM\models\Relation;
  use MoXIM\models\Relationship;

  use \RuntimeException;

  /* This class provides error checking to the gateway/dataprovider. */

  class BaseService
  {
    // Table ids
    const ASSIGNMENTS = 4;
    const MODULES = 1;
    const RELATIONS = 2;
    const RELATIONSHIPS = 3;

    public $default_options = array('sort' => array('id' => 'ASC'));
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
      if ($this->nodeExists($a->module, $a->node) === FALSE)
      {
        throw new RuntimeException('Node id "'.htmlspecialchars($a->node).'" does not exist in module with id "'.htmlspecialchars($a->module).'".');
      }
      return $this->gateway->addAssignment($a);
    }

    public function addModule(Module $m)
    {
      $m->validate();
      if ($this->gateway->getModuleId($m->name) !== FALSE)
      {
        throw new RuntimeException('Module "'.htmlspecialchars($m->name).'" already exists.');
      }
      return $this->gateway->addModule($m);
    }

    public function addRelation(Relation $r)
    {
      $r->validate();
      if ($this->gateway->getRelationId($r->domain, $r->name, $r->range) !== FALSE)
      {
        throw new RuntimeException('Relation "'.htmlspecialchars($this->gateway->getModule($r->domain)->name.' '.$r->name.' '.$this->gateway->getModule($r->range)->name).'" already exists.');
      }
      if ($this->gateway->getModule($r->domain) === FALSE)
      {
        throw new RuntimeException('Domain module id "'.htmlspecialchars($r->domain).'" does not exist.');
      }
      if ($this->gateway->getModule($r->range) === FALSE)
      {
        throw new RuntimeException('Range module id "'.htmlspecialchars($r->range).'" does not exist.');
      }
      return $this->gateway->addRelation($r);
    }

    public function addRelationship(Relationship $r)
    {
      $r->validate();
      if (($relation = $this->gateway->getRelation($r->relation)) === FALSE)
      {
        throw new RuntimeException('Relationship relation id "'.$r->relation.'" does not exist.');
      }
      if ($this->gateway->getRelationshipId($r->domain, $r->relation, $r->range) !== FALSE)
      {
        throw new RuntimeException('Relationship "'.htmlspecialchars($this->getModule($relation->domain)->name.'('.$r->domain.') '.$relation->name.' '.$this->gateway->getModule($relation->range)->name.'('.$r->range).')" already exists.');
      }
      if ($this->nodeExists($relation->domain, $r->domain) === FALSE)
      {
        throw new RuntimeException('Relationship domain id "'.htmlspecialchars($r->domain).'" does not exist in module "'.htmlspecialchars($this->getModule($relation->domain)->name).'".');
      }
      if ($this->nodeExists($relation->range, $r->range) === FALSE)
      {
        throw new RuntimeException('Relationship range id "'.htmlspecialchars($r->range).'" does not exist in module "'.htmlspecialchars($this->getModule($relation->range)->name).'".');
      }
      return $this->gateway->addRelationship($r);
    }

    private function _checkOptions($opts)
    {
      if (!is_null($opts))
      {
        if (!is_array($opts))
        {
          throw new RuntimeException('Options must be an associative array.');
        }
      } else {
        $opts = $this->default_options;
      }
      return $opts;
    }

    public function deleteAssignment($id)
    {
      $id = Assignment::validateId($id);
      if ($this->gateway->deleteAssignment($id) === FALSE)
      {
        throw new RuntimeException('Assignment id "'.htmlspecialchars($id).'" does not exist.');
      }
      return TRUE;
    }

    public function deleteModule($id)
    {
      $id = Module::validateId($id);
      if ($this->gateway->deleteModule($id) === FALSE)
      {
        throw new RuntimeException('Module id "'.htmlspecialchars($id).'" does not exist.');
      }
      return TRUE;
    }

    public function deleteRelation($id)
    {
      $id = Relation::validateId($id);
      if ($this->gateway->deleteRelation($id) === FALSE)
      {
        throw new RuntimeException('Relation id "'.htmlspecialchars($id).'" does not exist.');
      }
      return TRUE;
    }

    public function deleteRelationship($id)
    {
      $id = Relationship::validateId($id);
      if ($this->gateway->deleteRelationship($id) === FALSE)
      {
        throw new RuntimeException('Relationship id "'.htmlspecialchars($id).'" does not exist.');
      }
      return TRUE;
    }

    public function getAssignment($id)
    {
      $id = Assignment::validateId($id);
      if (($a = $this->gateway->getAssignment($id)) === FALSE)
      {
        throw new RuntimeException('Assignment id "'.htmlspecialchars($id).'" does not exist.');
      }
      return $a;
    }

    public function getAssignments($module = NULL, $node = NULL, $value = NULL, $opts = NULL)
    {
      if (!is_null($module))
      {
        $id = Module::validateId($id);
      }
      $opts = $this->_checkOptions($opts);
      return $this->gateway->getAssignments($module, $node, $value, $opts);
    }

    public function getModule($id)
    {
      $id = Module::validateId($id);
      if (($m = $this->gateway->getModule($id)) === FALSE)
      {
        throw new RuntimeException('Module id "'.htmlspecialchars($id).'" does not exist.');
      }
      return $m;
    }

    public function getModules($opts = NULL)
    {
      $opts = $this->_checkOptions($opts);
      return $this->gateway->getModules($opts);
    }

    public function getRelation($id)
    {
      $id = Relation::validateId($id);
      if (($r = $this->gateway->getRelation($id)) === FALSE)
      {
        throw new RuntimeException('Relation id "'.htmlspecialchars($id).'" does not exist.');
      }
      return $r;
    }

    public function getRelations($domain = NULL, $name = NULL, $range = NULL, $opts = NULL)
    {
      if (!is_null($domain))
      {
        $domain = Module::validateId($domain);
      }
      if (!is_null($range))
      {
        $range = Module::validateId($range);
      }
      $opts = $this->_checkOptions($opts);
      return $this->gateway->getRelations($domain, $name, $range, $opts);
    }

    public function getRelationship($id)
    {
      $id = Relationship::validateId($id);
      if (($r = $this->gateway->getRelationship($id)) === FALSE)
      {
        throw new RuntimeException('Relationship id "'.htmlspecialchars($id).'" does not exist.');
      }
      return $r;
    }

    public function getRelationships($domain = NULL, $relation = NULL, $range = NULL, $opts = NULL)
    {
      if (!is_null($domain))
      {
        $domain = Module::validateId($domain);
      }
      if (!is_null($relation))
      {
        $domain = Relation::validateId($domain);
      }
      if (!is_null($domain))
      {
        $domain = Module::validateId($domain);
      }
      $opts = $this->_checkOptions($opts);
      return $this->gateway->getRelationships($domain, $relation, $range, $opts);
    }

    private function nodeExists($module, $node)
    {
      return $this->gateway->nodeExists($this->getModule($module)->id, $node);
    }

    public function updateAssignment(Assignment $a)
    {
      $a->validate(Assignment::UPDATE);
      if ($this->gateway->updateAssignment($a) === FALSE)
      {
        throw new RuntimeException('Assignment id "'.htmlspecialchars($a->id).'" does not exist.');
      }
      return TRUE;
    }

    public function updateModule(Module $m)
    {
      $a->validate(Module::UPDATE);
      if ($this->gateway->updateModule($m) === FALSE)
      {
        throw new RuntimeException('Module id "'.htmlspecialchars($m->id).'" does not exist.');
      }
      return TRUE;
    }

    public function updateRelation(Relation $a)
    {
      $a->validate(Relation::UPDATE);
      if ($this->gateway->updateRelation($r) === FALSE)
      {
        throw new RuntimeException('Relation id "'.htmlspecialchars($r->id).'" does not exist.');
      }
      return TRUE;
    }

    public function updateRelationship(Relationship $a)
    {
      $a->validate(Relationship::UPDATE);
      if ($this->gateway->updateRelationship($r) === FALSE)
      {
        throw new RuntimeException('Relationship id "'.htmlspecialchars($r->id).'" does not exist.');
      }
      return TRUE;
    }
  }
?>
