<?php
  namespace MoXIM\services\dataproviders;
  use MoXIM\models\Assignment;
  use MoXIM\models\Module;
  use MoXIM\models\Relation;
  use MoXIM\models\Relationship;
  
  // This class is to be implemented by a specific data provider service

  interface IBaseGateway
  {
    // Required functions by BaseService

    function addAssignment(Assignment $a);
    function addModule(Module $m);
    function addRelation(Relation $r);
    function addRelationship(Relationship $r);

    function deleteAssignment($id);
    function deleteModule($id);
    function deleteRelation($id);
    function deleteRelationship($id);

    function getAssignment($id);
    function getAssignmentId($module, $node, $value);
    function getAssignments($module, $node, $value, $opts);
    function getModule($id);
    function getModuleId($name);
    function getModules($opts);
    function getRelation($id);
    function getRelationId($domain, $name, $range);
    function getRelations($domain, $name, $range, $opts);
    function getRelationship($id);
    function getRelationshipId($domain, $relation, $range);
    function getRelationships($domain, $relation, $range, $opts);

    function nodeExists($module, $node);

    function updateAssignment(Assignment $a);
    function updateModule(Module $m);
    function updateRelation(Relation $r);
    function updateRelationship(Relationship $r);
  }
?>
