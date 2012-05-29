<?php
  namespace MoXIM\models;
  use RuntimeException;

  abstract class Node
  {
    const NEW_ID = 0;
    const MAX_ID = PHP_INT_MAX;
    const MIN_ID = 1;
  
    public $id;
  
    protected function validate()
    {
      if ($this->id != self::NEW_ID)
      {
        $this->id = self::validateId($this->id);
      }
      return $this;
    }
  
    static public function validateId($id)
    {
      if (is_null($id))
      {
        throw new RuntimeException(get_called_class().' id is required.');
      }
      if (($id = filter_var($id, FILTER_VALIDATE_INT, array('options' => array('min_range' => self::MIN_ID, 'max_range' => self::MAX_ID)))) === FALSE)
      {
        throw new RuntimeException(get_called_class().' id must be a positive integer, '.gettype($id).'('.htmlspecialchars($id).') given.');
      }
      return $id;
    }
  
    static protected function validateString($str, $prop, $len, $required = TRUE)
    {
      if ($required)
      {
        if (strlen($str) == 0)
        {
          throw new RuntimeException(get_called_class().' '.$prop.' is required.');
        }
      }
      if (strlen($str) > $len)
      {
        throw new RuntimeException(get_called_class().' '.$prop.' must be less than '.$len.' characters.');
      }
      return $str;
    }
  }
?>
