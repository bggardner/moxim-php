<?php
  use MoXIM\services\BaseService as Service;
  require_once 'config.php';
  $o = new stdClass();
  $o->driver = 'pdo/mysql';
  $o->username = '';
  $o->password = '';
  $o->dbname = 'moxim';
  $svc = new Service($o);
  // List modules to demonstrate some functionality
  foreach ($svc->getModules() as $x)
  {
    echo $x->name . '<br />';
    switch ($x->id)
    {
      case Service::ASSIGNMENTS:
        array_walk(
          $svc->getAssignments(),
          function ($o, $i, $svc)
          {
            echo ' - ('.$o->id.'): ';
            echo $svc->getModule($o->module)->name;
            echo '('.$o->node.') = '.htmlspecialchars($o->value);
            echo '<br />';
          },
          $svc
        );
        break;
      case Service::MODULES:
        array_walk(
          $svc->getModules(),
          function($o)
          {
            echo ' - ('.$o->id.'): ';
            echo $o->name.'<br />';
          }
        );
        break;
      case Service::RELATIONS:
        array_walk(
          $svc->getRelations(),
          function($o, $i, $svc)
          {
            echo ' - ('.$o->id.'): ';
            echo $svc->getModule($o->domain)->name;
            echo ' '.$o->name.' ';
            echo $svc->getModule($o->range)->name;
            echo '<br />';
          },
          $svc
        );
        break;
      case Service::RELATIONSHIPS:
        array_walk(
          $svc->getRelationships(),
          function($o, $i, $svc)
          {
            $r = $svc->getRelation($o->relation);
            echo ' - ('.$o->id.'): ';
            echo $svc->getModule($r->domain)->name.'('.$o->domain.')';
            echo ' '.$svc->getRelation($o->relation)->name.' ';
            echo $svc->getModule($r->range)->name.'('.$o->range.')';
            echo '<br />';
          },
          $svc
        );
        break;
    }
  }
?>
