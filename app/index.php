<?php
  use MoXIM\services\BaseService as Service;
  require_once 'config.php';
  $o = new stdClass();
  $o->driver = 'pdo/mysql';
  $o->username = '';
  $o->password = '';
  $o->dbname = 'moxim';
  $svc = new Service($o);

  $a = new MoXIM\models\Assignment();
  $m = new MoXIM\models\Module();
  $m->name = 'moxim_relationships';
  $a->id = 2;
  $a->module = $svc->get($m)->id;
  $a->node = 1;
  $a->value = 'hello';
  $svc->update($a);

  if ($_GET["add"])
  {
    if ($_POST)
    {
      $class = 'MoXIM\\models\\'.$_GET["add"];
      $n = new $class();
      foreach ($_POST as $key => $value)
      {
        $n->$key = $value;
      }
      $svc->add($n);
      header('Location: '.$_SERVER["PHP_SELF"]);
      exit;
    }
    switch ($_GET["add"])
    {
      case 'Module':
        $inputs[] = array('id' => 'name', 'name' => 'name', 'type' => 'string');
        break;
      case 'Relation':
        break;
      case 'Relationship':
        $inputs[] = array('id' => 'source', 'name' => 'source', 'type' => 'string');
        $inputs[] = array('id' => 'relation', 'name' => 'relation', 'type' => 'Relation');
        $inputs[] = array('id' => 'target', 'name' => 'target', 'type' => 'string');
        break;
      case 'Assignment':
        $inputs[] = array('id' => 'module', 'name' => 'module', 'type' => 'Module');
        $inputs[] = array('id' => 'node', 'name' => 'node', 'type' => 'string');
        $inputs[] = array('id' => 'value', 'name' => 'value', 'type' => 'string');
        break;
      default:
        throw new RuntimeException(htmlspecialchars($_GET["add"]).' not supported.');
    }
    echo '
<form action="" method="post">
  <fieldset>
    <legend>Add '.htmlspecialchars($_GET["add"]).'</legend>';
    foreach ($inputs as $input)
    {
      echo '
    <label for="'.$input["id"].'">'.ucwords($input["name"]).'</label>';
      switch ($input["type"])
      {
        case 'string':
          echo '
    <input id="'.$input["id"].'" name="'.$input["name"].'" type="text" />';
          break;
        default:
          echo '
    <select id="'.$input["id"].'" name="'.$input["name"].'">';
          $f = 'get'.$input["type"].'s';
          foreach ($svc->$f() as $n)
          {
            echo '
      <option value="'.$n->id.'">'.($n->name ?: $n->id).'</option>';
          }
          echo '
    </select>';
      }
      echo '<br />';
    }
    echo '
    <input type="submit" value="Submit" />
  </fieldset>
</form>';
    exit;
  }

  if ($n = $_GET["node"])
  {
    $m = $svc->getModule($_GET["module"]);
    echo '
<h3>'.htmlspecialchars($m->name.'('.$_GET['node'].')').'</h3>
<h4>Relationships <a href="'.$_SERVER["PHP_SELF"].'?add=Relationship">add...</a></h4>
<table>';
    $source_relations = $svc->getRelations($m->id);
    $relationships = array();
    $target_relations = $svc->getRelations(NULL, NULL, $m->id);
    foreach ($source_relations as $relation)
    {
      $m_target = $svc->getModule($relation->target);
      foreach ($svc->getRelationships($n, $relation->id) as $r)
      {
        echo '
  <tr>
    <td>'.htmlspecialchars($m->name.'('.$n.')').'</td>
    <td>'.htmlspecialchars($relation->name).'</td>
    <td>'.htmlspecialchars($m_target->name.'('.$r->target.')').'</td>
  </tr>';
      }
    }
    foreach ($target_relations as $relation)
    {
      $m_source = $svc->getModule($relation->source);
      foreach ($svc->getRelationships(NULL, $relation->id, $n) as $r)
      {
        echo '
  <tr>
    <td>'.htmlspecialchars($m_source->name.'('.$r->target.')').'</td>
    <td>'.htmlspecialchars($relation->name).'</td>
    <td>'.htmlspecialchars($m->name.'('.$n.')').'</td>
  </tr>';
      }
    }
    echo '
</table>
<h4>Assignments <a href="'.$_SERVER["PHP_SELF"].'?add=Assignment">add...</a></h4>
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
    <td>'.htmlspecialchars($m->name).'</td>
    <td>'.htmlspecialchars($n).'</td>
    <td>'.htmlspecialchars($a->value).'</td>
  </tr>';
    }
    echo '
</table>';
    exit;
  }

  if ($_GET["relation"])
  {
    $relation = $svc->getRelation($_GET["relation"]);
    $source = $svc->getModule($relation->source);
    $target = $svc->getModule($relation->target);
    echo '
<h3>'.htmlspecialchars($source->name.' '.$relation->name.' '.$target->name).'</h3>
<h4>Relationships</h3>
<table>
  <tr>
    <th>'.htmlspecialchars($source->name).' id</th>
    <th>'.htmlspecialchars($target->name).' id</th>
  </tr>';
  foreach ($svc->getRelationships(NULL, $relation->id) as $r)
    {
      echo '
  <tr>
    <td>'.htmlspecialchars($r->source).'</td>
    <td>'.htmlspecialchars($r->target).'</td>
  </tr>';
    }
    echo '
</table>';
    exit;
  }

  echo '
<table style="width: 100%;">
  <tr>
    <td colspan="2">';
  foreach ($svc->getModules() as $m)
  {
    $menu[] = '<a href="'.$_SERVER["PHP_SELF"].'?module='.$m->id.'">'.$m->name.'</a>';
  }
  $menu[] = '<a href="'.$_SERVER["PHP_SELF"].'?add=Module">add...</a>';
  echo implode(' | ', $menu).'<br />';

  $m = $svc->getModule($_GET['module'] ?: 1);
  echo '
<h3>'.$m->name.'</h3>
    </td>
  </tr>
  <tr>
    <td style="width: 50%;">
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

<h4>Relations <a href="'.$_SERVER["PHP_SELF"].'?add=Relation">add...</a></h4>
<table>
  <tr>
    <th>id</th>
    <th>source</th>
    <th>name</th>
    <th>target</th>
  </tr>';
  foreach (array_merge($svc->getRelations($m->id), $svc->getRelations(NULL, NULL, $m->id)) as $r)
  {
    echo '
  <tr onclick="document.getElementById(\'nodeView\').src = \''.$_SERVER['PHP_SELF'].'?relation='.$r->id.'\';" style="cursor: pointer;">
    <td>'.$r->id.'</td>
    <td>'.$svc->getModule($r->source)->name.'</td>
    <td>'.$r->name.'</td>
    <td>'.$svc->getModule($r->target)->name.'</td>
  </tr>';
  }
  echo '
</table>
    </td>
    <td style="width: 50%;">
      <iframe id="nodeView" style="width: 100%; height: 100%;"></iframe>
    </td>
  </tr>
</table>';

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
            echo $svc->getModule($o->source)->name;
            echo ' '.$o->name.' ';
            echo $svc->getModule($o->target)->name;
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
            echo $svc->getModule($r->source)->name.'('.$o->source.')';
            echo ' '.$svc->getRelation($o->relation)->name.' ';
            echo $svc->getModule($r->target)->name.'('.$o->target.')';
            echo '<br />';
          },
          $svc
        );
        break;
    }
  }
*/
?>
