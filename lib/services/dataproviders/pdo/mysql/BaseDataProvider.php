<?php
  namespace MoXIM\services\dataproviders\pdo\mysql;

  class BaseDataProvider extends \MoXIM\services\dataproviders\pdo\BaseDataProvider
  {
    public function __construct($o)
    {
      /*
       * Add some error checking here
       * Look into passing the DSN in $o
      */
      parent::__construct('mysql:host='.$o->host, $o->username, $o->password);
    }

    static public function _init()
    {
      return file_get_contents(realpath(__DIR__ . '/moxim-php.sql'));
    }
  }
?>
