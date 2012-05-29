<?php

  namespace MoXIM\services\dataproviders\gateways\pdo;

  use MoXIM\models\Assignment;
  use MoXIM\models\Module;
  use MoXIM\models\Relation;
  use MoXIM\models\Relationship;
  
  // This class is to be implemented by a specific PDO gateway

  interface IBaseGateway
  {
    // Required functions by BaseService
    static function _getAssignment($id);
    static function _getAssignments($module, $node, $value, $opts);
    static function _getModule($id);
    static function _getModuleId($name);
    static function _getModules($opts);
    static function _getRelation($id);
    static function _getRelationId($source, $name, $target);
    static function _getRelations($source, $name, $target, $opts);
    static function _getRelationship($id);
    static function _getRelationshipId($source, $relation, $target);
    static function _getRelationships($source, $relation, $target, $opts);
    static function _moduleExists($name);
    static function _nodeExists(Module $module, $node);
  }

?>
