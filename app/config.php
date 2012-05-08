<?php
  // Set moxim-php directory (absolute path)
     define('__MOXIM_DIR__', __DIR__ . '/../lib');

  /* Class autoloader: Will automatically load classes, translating
     namespaces to directories and class names to .php files.
     You may specify custom namespaces prefixes in the switch
     statement to point to paths different from the current directoy.
     You may use your own __autoload function by commenting this out,
     but MoXIM classes will need to be loaded manually.  All MoXIM
     class namespaces start with "MoXIM\".

     Example:
     $myClass = new MyApp\MyClass(); will throw:
     __autoload('MyApp\MyClass');
     "MyApp" is the prefix.  If "MyApp" is a case in the switch
     statment where $classPath is defined as "/var/www/classes/",
     "/var/www/classes/MyClass.php" will be loaded.  If "MyApp" is
     not defined as a case in the switch statement,
     "<current directory>/MyApp/classes/MyClass.php" will be loaded.
  */
  function __autoload($class)
  {
    if (($pos = strpos($class, '\\')) !== FALSE)
    {
      $prefix = substr($class, 0, $pos);
      $class = substr($class, $pos);
    }
    switch ($prefix)
    {
      case 'MoXIM':
        $classPath = __MOXIM_DIR__;
        break;
      default:
        // Default to current directory
        list($class) = func_get_args();
        $classPath = __DIR__ . '/';
    }
    $classPath .= str_replace('\\', '/', $class) . '.php';
    if (($realClassPath = realpath($classPath)) === FALSE)
    {
      throw new RuntimeException('Invalid class path "'.htmlspecialchars($classPath).'".');
    }
    require_once $realClassPath;
  }
?>
