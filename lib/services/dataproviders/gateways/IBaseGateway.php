<?php

  namespace MoXIM\services\dataproviders\gateways;

  use MoXIM\models\Assignment;
  use MoXIM\models\Module;
  use MoXIM\models\Relation;
  use MoXIM\models\Relationship;
  
  // This class is to be implemented by a specific data provider service

  interface IBaseGateway
  {
    // Required functions by BaseService
    function getAssignments($module, $node, $value, $opts);
    function getModuleId($name);
    function getModules($opts);
    function getRelationId($source, $name, $target);
    function getRelations($source, $name, $target, $opts);
    function getRelationshipId($source, $relation, $target);
    function getRelationships($source, $relation, $target, $opts);
    function moduleExists($name);
    function nodeExists(Module $module, $node);
  }

?>
