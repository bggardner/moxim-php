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
    static function _getAssignments($module, $node, $value, $opts);
    static function _getModules($opts);
    static function _getNodes(Module $module, $opts);
    static function _getRelations($source, $name, $target, $opts);
    static function _getRelationships($source, $relation, $target, $opts);
    static function _moduleExists($name);
    static function _nodeExists(Module $module, $node);
  }

?>
