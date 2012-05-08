<?php
  namespace MoXIM\services\dataproviders\pdo;
  use MoXIM\models\Assignment;
  use MoXIM\models\Module;
  use MoXIM\models\Relation;
  use MoXIM\models\Relationship;
  
  // This class is to be implemented by a specific PDO gateway

  interface IBaseGateway
  {
    // Required functions by BaseService

    static function _addAssignment(Assignment $a);
    static function _addModule(Module $m);
    static function _addRelation(Relation $r);
    static function _addRelationship(Relationship $r);

    static function _deleteAssignment($id);
    static function _deleteModule($id);
    static function _deleteRelation($id);
    static function _deleteRelationship($id);

    static function _getAssignment($id);
    static function _getAssignmentId($module, $node, $value);
    static function _getAssignments($module, $node, $value, $opts);
    static function _getModule($id);
    static function _getModuleId($name);
    static function _getModules($opts);
    static function _getRelation($id);
    static function _getRelationId($domain, $name, $range);
    static function _getRelations($domain, $name, $range, $opts);
    static function _getRelationship($id);
    static function _getRelationshipId($domain, $relation, $range);
    static function _getRelationships($domain, $relation, $range, $opts);

    static function _init();
    static function _nodeExists($module, $node)

    static function _updateAssignment(Assignment $a);
    static function _updateModule(Module $m);
    static function _updateRelation(Relation $r);
    static function _updateRelationship(Relationship $r);
  }
?>
