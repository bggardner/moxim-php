<?php
  // Set moxim-php directory (absolute path)
  define('__MOXIM_DIR__', __DIR__ . '/../lib');

  // Add MoXIM class autoloader to stack
  spl_autoload_register('moxim_autoload', TRUE, TRUE);

  function moxim_autoload($class)
  {
    if (($pos = strpos($class, '\\')) !== FALSE)
    {
      if (substr($class, 0, $pos) == 'MoXIM')
      {
        $class = substr($class, $pos);
        $classPath = __MOXIM_DIR__ . str_replace('\\', '/', $class) . '.php';
        if (($realClassPath = realpath($classPath)) === FALSE)
        {
          throw new RuntimeException('Invalid class path "'.htmlspecialchars($classPath).'".');
        }
        require_once $realClassPath;
      }
    }
  }
?>
