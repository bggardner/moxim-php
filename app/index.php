<?php
  require_once 'services/BaseService.php';
  $o = new stdClass();
  $o->driver = 'pdo/mysql';
  $o->username = '';
  $o->password = '';
  $o->dbname = 'moxim';
  $svc = new BaseService($o);
  var_dump($svc);
?>
