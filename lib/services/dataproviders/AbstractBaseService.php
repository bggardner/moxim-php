<?php
  namespace MoXIM\services\dataproviders;
  use MoXIM\models\Module;
  use MoXIM\models\Relation;
  
  // This class is extended by a specific database service
  abstract class AbstractBaseService
  {
    protected $dp;
  
    // Required functions by BaseService
    abstract protected function addModule(Module $m);
    abstract protected function addRelation(Relation $r);
    abstract protected function __construct($o);
    abstract protected function deleteModule($id);
    abstract protected function deleteRelation($id);
    abstract protected function idExists($module, $id);
    abstract protected function updateModule(Module $m);
    abstract protected function updateRelation(Relation $r);
  }
?>
