<?php
  namespace MoXIM\services;
  use MoXIM\models\Module;
  use MoXIM\models\Relation;
  
  use \RuntimeException;
  
  /* This class provides error checking to the extended dataprovider. */
  
  class BaseService extends dataproviders\pdo\BaseService
  {
    const MODULES = 'modules';
    const RELATIONS = 'relations';
    
    public function addModule(Module $m)
    {
      $m->validate();
      return parent::addModule($m);
    }
    
    public function addRelation(Relation $r)
    {
      $r->validate();
      if (!parent::idExists(self::MODULES, $r->domain))
      {
        throw new RuntimeException('Domain module id "'.htmlspecialchars($r->domain).'" does not exist.');
      }
      if (!parent::idExists(self::MODULES, $r->range))
      {
        throw new RuntimeException('Range module id "'.htmlspecialchars($r->domain).'" does not exist.');
      }
      return parent::addRelation($r);
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
