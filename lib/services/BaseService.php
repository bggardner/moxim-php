<?php

  namespace MoXIM\services;

  use MoXIM\models\Assignment;
  use MoXIM\models\Module;
  use MoXIM\models\Node;
  use MoXIM\models\Relation;
  use MoXIM\models\Relationship;

  use MoXIM\utils\Exception;

  /* This class provides error checking to the data provider. */

  class BaseService
  {
    public $default_options = array('sort' => array('id' => 'ASC'));

    private $dp;
    public  $ds; // Possibly delete later

    public function __construct($o)
    {
      if ((include_once 'dataproviders/' . $o->driver . '/BaseDataProvider.php') === FALSE)
      {
        throw new Exception('Driver "'.htmlspecialchars($o->driver).'" does not exist.');
      }
      $driverClass = __NAMESPACE__ . '\\dataproviders\\' . str_replace('/', '\\', $o->driver) . '\\BaseDataProvider';
      $this->dp = new $driverClass($o);
      if (($this->dp instanceof dataproviders\BaseDataProvider) === FALSE)
      {
        throw new Exception('Invalid data provider "'.htmlspecialchars($o->driver).'".  Must extend BaseDataProvider.');
      }
      $this->ds = $this->dp->ds; // Make datasource publicly accessible, possibly remove later
    }

    /* Map [command][model]() to [command]() */

    public function __call($name, $args)
    {
      if (substr($name, 0, 3) == 'add')
      {
        return $this->add($args[0]);
      }
      if (substr($name, 0, 6) == 'delete')
      {
        $class = 'MoXIM\\models\\'.substr($name, 6);
        $n = new $class();
        $n->id = $args[0];
        return $this->delete($n);
      }
      if (substr($name, 0, 3) == 'get')
      {
        $class = 'MoXIM\\models\\'.substr($name, 3);
        $n = new $class();
        $n->id = $args[0];
        return $this->get($n);
      }
      if (substr($name, 0, 6) == 'update')
      {
        return $this->update($args[0]);
      }
    }

    /* Basic methods */
    public function add(Node $n)
    {
      $class = get_class($n);
      $n->id = $class::NEW_ID;
      return $this->save($n);
    }

    public function delete(Node $n)
    {
      $class = get_class($n);
      $n->id = $class::validateId($n->id);
      if ($this->dp->delete($n) === FALSE)
      {
        throw new Exception(basename($class).' id "'.htmlspecialchars($n->id).'" does not exist.');
      }
      return TRUE;
    }

    public function get(Node $n)
    {
      $class = get_class($n);
      if (isset($n->id))
      {
        $id = $class::validateId($n->id);
        $n = new $class();
        $n->id = $id;
      } else {
        $n->id = $class::NEW_ID;
        $n->validate();
        $n->id = NULL;
      }
      if (($n = $this->dp->get($n)) === FALSE)
      {
        throw new Exception(basename($class).' id "'.htmlspecialchars($n->id).'" does not exist.');
      }
      return $n;
    }

    public function update(Node $n)
    {
      $class = get_class($n);
      $n2 = new $class();
      $n2->id = $n->id;
      if ($this->dp->get($n2) === FALSE)
      {
        throw new Exception('Update failed: '.basename($class).' id "'.htmlspecialchars($n->$id).'" does not exist.');
      }
      return $this->save($n);
    }

    /* Helper methods */
    protected function checkOptions($opts)
    {
      if (!is_null($opts))
      {
        if (!is_array($opts))
        {
          throw new Exception('Options must be an associative array.');
        }
      } else {
        $opts = $this->default_options;
      }
      return $opts;
    }

    protected function nodeExists($module, $node)
    {
      $m = new Module();
      $m->id = $module;
      $module = $this->dp->get($m);
      return $this->dp->nodeExists($module, $node);
    }

    protected function save(Node $n)
    {
      $n->validate();
      $class = get_class($n);
      $f = 'save' . basename(str_replace('\\', '/', $class));
      $n = $this->$f($n); // Check foreign keys and uniqueness
      if ($n->id == $class::NEW_ID)
      {
        $n->id = NULL;
        return $this->dp->add($n);
      } else {
        return $this->dp->update($n);
      }
    }

    protected function saveAssignment(Assignment $a)
    {
      // Check if node exists
      if ($this->nodeExists($a->module, $a->node) === FALSE)
      {
        $m = new Module();
        $m->id = $a->module;
        throw new Exception('Node id "'.htmlspecialchars($a->node).'" does not exist in module with id "'.htmlspecialchars($this->dp->get($m)->name).'".');
      }
      return $a;
    }

    protected function saveModule(Module $m)
    {
      // Check for existence
      if (!$this->dp->moduleExists($m->name))
      {
        throw new Exception('Module "'.htmlspecialchars($m->name).'" does not exist.');
      }
      // Check for unique name
      $m2 = clone $m;
      $m2->id = NULL;
      $id = $this->dp->get($m2)->id;
      if (($id !== FALSE) && ($id != $m->id))
      {
        throw new Exception('Module "'.htmlspecialchars($m->name).'" already exists.');
      }
      return $m;
    }

    protected function saveRelation(Relation $r)
    {
      // Check if source Module exists
      $ms = new Module();
      $ms->id = $r->source;
      if (($ms = $this->dp->get($ms)) === FALSE)
      {
        throw new Exception('Source module id "'.htmlspecialchars($r->source).'" does not exist.');
      }
      var_dump($ms);
      // Check if target Module exists
      $mt = new Module();
      $mt->id = $r->target;
      if (($mt = $this->dp->get($mt)) === FALSE)
      {
        throw new Exception('Target module id "'.htmlspecialchars($r->target).'" does not exist.');
      }
      // Check uniqueness
      $r2 = clone $r;
      $r2->id = NULL;
      $id = $this->dp->get($r2)->id;
      if (($id !== FALSE) && ($id != $r->id))
      {
        throw new Exception('Relation "'.htmlspecialchars($ms->name.' '.$r->name.' '.$mt->name).'" already exists.');
      }
      return $r;
    }

    protected function saveRelationship(Relationship $r)
    {
      // Check if Relation exists
      $relation = new Relation();
      $relation->id = $r->relation;
      if (($relation = $this->dp->get($relation)) === FALSE)
      {
        throw new Exception('Relationship relation id "'.$r->relation.'" does not exist.');
      }
      // Check if source Node exists
      if ($this->nodeExists($relation->source, $r->source) === FALSE)
      {
        $m = new Module();
        $m->id = $relation->source;
        throw new Exception('Relationship source id "'.htmlspecialchars($r->source).'" does not exist in module "'.htmlspecialchars($this->dp->get($m)->name).'".');
      }
      // Check if target Node exists
      if ($this->nodeExists($relation->target, $r->target) === FALSE)
      {
        $m = new Module();
        $m->id = $relation->target;
        throw new Exception('Relationship target id "'.htmlspecialchars($r->target).'" does not exist in module "'.htmlspecialchars($this->dp->get($m)->name).'".');
      }
      // Check uniqueness
      $r2 = clone $r;
      $r2->id = NULL;
      $id = $this->dp->get($r2)->id;
      if (($id !== FALSE) && ($id != $r->id))
      {
        $ms = new Module();
        $ms->id = $relation->source;
        $mt = new Module();
        $mt->id = $relation->target;
        throw new Exception('Relationship "'.htmlspecialchars($this->dp->get($ms)->name.'('.$r->source.') '.$relation->name.' '.$this->dp->get($mt)->name.'('.$r->target).')" already exists.');
      }
      return $r;
    }

    /* Complex methods */
    public function getAssignments($module = NULL, $node = NULL, $value = NULL, $opts = NULL)
    {
      if (!is_null($module))
      {
        $m = new Module();
        $m->id = $module;
        $module = $this->get($m)->id;
      }
      if (!is_null($node))
      {
        $node = Node::validateId($node);
      }
      $opts = $this->checkOptions($opts);
      return $this->dp->getAssignments($module, $node, $value, $opts);
    }

    public function getModules($opts = NULL)
    {
      $opts = $this->checkOptions($opts);
      return $this->dp->getModules($opts);
    }

    public function getNodes($module, $opts = NULL)
    {
      $m = new Module();
      $m->id = $module;
      $module = $this->get($m);
      $opts = $this->checkOptions($opts);
      return $this->dp->getNodes($module, $opts);
    }

    public function getRelations($source = NULL, $name = NULL, $target = NULL, $opts = NULL)
    {
      if (!is_null($source))
      {
        $m = new Module();
        $m->id = $source;
        $source = $this->get($m)->id;
      }
      if (!is_null($target))
      {
        $m = new Module();
        $m->id = $target;
        $target = $this->get($m)->id;
      }
      $opts = $this->checkOptions($opts);
      return $this->dp->getRelations($source, $name, $target, $opts);
    }

    public function getRelationships($source = NULL, $relation = NULL, $target = NULL, $opts = NULL)
    {
      if (!is_null($source))
      {
        $source = Node::validateId($source);
      }
      if (!is_null($relation))
      {
        $r = new Relation();
        $r->id = $relation;
        $relation = $this->get($r)->id;
      }
      if (!is_null($target))
      {
        $target = Node::validateId($target);
      }
      $opts = $this->checkOptions($opts);
      return $this->dp->getRelationships($source, $relation, $target, $opts);
    }
  }

?>
