<?php
  define('__MOXIM_LIB__', __DIR__ . '/../../lib/');

  function __autoload($class)
  {
echo $class . '<br/>';
    if (substr($class,0,5) == 'MoXIM')
    {
      $moxim_class = str_replace('\\', '/', substr($class, 6));
      require_once realpath(__MOXIM_LIB__ . $moxim_class . '.php');
    }
  }
  
  class BaseService extends \MoXIM\services\BaseService
  {
    
  }
?>
