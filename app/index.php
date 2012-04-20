<?php
  require_once 'config.php';
  $o = new stdClass();
  $o->driver = 'pdo/mysql';
  $o->username = '';
  $o->password = '';
  $o->dbname = 'moxim';
  $svc = new services\BaseService($o);
  var_dump($svc);
?>
