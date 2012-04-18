<?php
  require_once 'services/BaseService.php';
  $o = new stdClass();
  $o->dbname = 'moxim';
  $svc = new BaseService($o);
  var_dump($svc);
?>
