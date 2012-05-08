<?php
  namespace MoXIM\models;
  
  abstract class Node
  {
    const UPDATE = 1;
  
    public $id;
  
    protected function validate($flags = 0)
    {
      if ($flags & self::UPDATE)
      {
        $this->id = self::validateId($this->id);
      }
    }
  
    static public function validateId($id)
    {
      if (is_null($id))
      {
        throw RuntimeException(get_called_class().' id is required.');
      }
      if (($id = filter_var($id, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)))) === FALSE)
      {
        throw RuntimeException(get_called_class().' id must be a positive integer, '.gettype($id).'('.htmlspecialchars($id).') given.');
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
