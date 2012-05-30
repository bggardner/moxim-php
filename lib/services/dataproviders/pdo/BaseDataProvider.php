<?php
  namespace MoXIM\services\dataproviders\pdo;

  use PDO;

  require_once realpath('../../classes/MySQLPDO.php'); // Delete later

  abstract class BaseDataProvider extends \MoXIM\services\dataproviders\BaseDataProvider
  {
    public function __construct($dsn, $username, $password)
    {
      //$ds = new PDO($dsn, $username, $password);
      $ds = new \eBrent\MySQLPDO('moxim'); // Delete later

      // Turn off database native prepares since most queries are unique
      $ds->setAttribute(PDO::ATTR_EMULATE_PREPARES, TRUE);
      $ds->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Initialize database
      $stmt = $ds->prepare(static::_init());
      $stmt->execute();

      parent::__construct($ds, 'pdo/' . $ds->getAttribute(PDO::ATTR_DRIVER_NAME));
    }

    abstract static public function _init();
  }
?>
