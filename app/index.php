<?php
  use MoXIM\services\BaseService as Service;
  require_once 'config.php';
  $o = new stdClass();
  $o->driver = 'pdo/mysql';
  $o->username = '';
  $o->password = '';
  $o->dbname = 'moxim';
  $svc = new Service($o);

  if ($n = $_GET['node'])
  {
    $m = $svc->getModule($_GET['module']);
    echo '
<h3>'.htmlspecialchars($m->name.'('.$_GET['node'].')').'</h3>
<h4>Relationships</h4>
<table>';
    $domain_relations = $svc->getRelations($m->id);
    $relationships = array();
    $range_relations = $svc->getRelations(NULL, NULL, $m->id);
    foreach ($domain_relations as $relation)
    {
      $m_range = $svc->getModule($relation->range);
      foreach ($svc->getRelationships($n, $relation->id) as $r)
      {
        echo '
  <tr>
    <td>'.htmlspecialchars($m->name.'('.$n.')').'</td>
    <td>'.htmlspecialchars($relation->name).'</td>
    <td>'.htmlspecialchars($m_range->name.'('.$r->range.')').'</td>
  </tr>';
      }
    }
    foreach ($range_relations as $relation)
    {
      $m_domain = $svc->getModule($relation->domain);
      foreach ($svc->getRelationships(NULL, $relation->id, $n) as $r)
      {
        echo '
  <tr>
    <td>'.htmlspecialchars($m_domain->name.'('.$r->range.')').'</td>
    <td>'.htmlspecialchars($relation->name).'</td>
    <td>'.htmlspecialchars($m->name.'('.$n.')').'</td>
  </tr>';
      }
    }
echo '
</table>
<h4>Assignments</h4>
<table>
  <tr>
    <th>module</th>
    <th>node</th>
    <th>value</th>
  </tr>';
    foreach ($svc->getAssignments($m->id, $n) as $a)
    {
      echo '
  <tr>
    <td>'.htmlspecialchars($m-name).'</td>
    <td>'.htmlspecialchars($n).'</td>
    <td>'.htmlspecialchars($a->value).'</td>
  </tr>';
    }
    echo '
</table>';
    exit;
  }

  foreach ($svc->getModules() as $m)
  {
    $menu[] = '<a href="'.$_SERVER['PHP_SELF'].'?module='.$m->id.'">'.$m->name.'</a>';
  }
  echo implode(' | ', $menu).'<br />';

  $m = $svc->getModule($_GET['module'] ?: 1);
  echo '
<h3>'.$m->name.'</h3>
<h4>Nodes</h4>
<table>';
  foreach ($svc->getNodes($m->id) as $i => $n)
  {
    if ($i == 0)
    {
      echo '
  <tr>';
      foreach ($n as $key => $value)
      {
        echo '
    <th>'.$key.'</th>';
      }
      echo '
  </tr>';
    }
    echo '
  <tr onclick="document.getElementById(\'nodeView\').src = \''.$_SERVER['PHP_SELF'].'?module='.$m->id.'&amp;node='.$n['id'].'\';" style="cursor: pointer;">';
    foreach ($n as $value)
    {
      echo '
    <td>'.$value.'</td>';
    }
    echo '
  </tr>';
  }
  echo '
</table>

<h4>Relations</h4>
<table>
  <tr>
    <th>id</th>
    <th>domain</th>
    <th>name</th>
    <th>range</th>
  </tr>';
  foreach (array_merge($svc->getRelations($m->id), $svc->getRelations(NULL, NULL, $m->id)) as $r)
  {
    echo '
  <tr>
    <td>'.$r->id.'</td>
    <td>'.$svc->getModule($r->domain)->name.'</td>
    <td>'.$r->name.'</td>
    <td>'.$svc->getModule($r->range)->name.'</td>
  </tr>';
  }
  echo '
</table>
<iframe id="nodeView" style="width: 100%; height: 100%;"></iframe>';

  // List modules to demonstrate some functionality
/*
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
*/
?>
