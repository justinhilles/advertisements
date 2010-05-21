<?php

if(!function_exists('select_tag')):
  function select_tag( $name , $options = array() , $value = null , $attributes = NULL , $sort = NULL )
  {
    if(is_array($attributes))
    {
      $attributes = implode(" ", $attributes);
    }
    $ret_data = "<select name=\"" . $name . "\" " . $attributes . ">\n";
    $ret_data .= "<option value=\"&nbsp;\">&nbsp;</option>\n";
    foreach($options as $optionvalue => $optiontext)
    {
      $ret_data .= "<option value=\"" . $optionvalue . "\" ";
      if($optionvalue == $value && $value != NULL)
      {
        $ret_data .= " SELECTED";
        $value = NULL;
      }
      $ret_data .= ">" . $optiontext . "</option>\n";
    }
    $ret_data .= "</select>";
    return $ret_data;
  }
endif;

if(!function_exists('convertToOptionsArray')):
  function convertToOptionsArray( $data = array() , $fields = null)
  {
    if(!empty($fields))
    {
      $i = 0;
      foreach($data as $single ){
        foreach($fields as $field => $value )
        {
          $values[$i][ $field ] = ( array_key_exists( $field , $single ) ? $single[ $field ] : NULL);
        }
        $i++;
      }
    }
    else
    {
      $values = $data;
    }
    foreach(array_values($values) as $option)
    {
      $option = array_values($option);
      $results[$option[0]] = $option[1];
    }
    return $results;
  }
endif;
?>