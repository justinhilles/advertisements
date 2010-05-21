<?php

if(!function_exists('messages')):
  function messages( $published_id = NULL )
  {
    $ret_data = NULL;
    if(isset($_SESSION['error']))
    {
      $ret_data .= '<div id="message" class="error fade"><p>' . $_SESSION['error'] . '</p></div>';
      unset($_SESSION['error']);
    }
    else if(isset($_SESSION['message']) && isset($published_id))
    {
      $ret_data .= '<div id="message" class="updated fade">';
      $ret_data .= '<p><strong>' . $_SESSION['message'] . '</strong>';
      $ret_data .= '<a href="' . get_permalink($published_id) . '">View post &raquo;</a></p>';
      $ret_data .= '</div>';
      unset($_SESSION['message']);
    }
    echo $ret_data;
  }
endif;
	
if(!function_exists('showColumns')):
  function showColumns( $table ){
    global $wpdb;
    $fields =  $wpdb->get_results("SHOW COLUMNS FROM $table");
    foreach($fields as $field){$temp[$field->Field] = $field->Field;}
    return $temp;
  }
endif;

if(!function_exists('get_vars')):
  function get_vars() {
    global $values;
    array_walk_recursive( $_REQUEST , 'values' );
    return $values;
  }
endif;

if(!function_exists('values')):
  function values( $value , $key ){
    global $values;
    $values[$key] = $value;
  }
endif;

if(!function_exists('a2o')):
  function a2o($array)
  {
    $object = new stdClass();
    if (is_array($array) && count($array) > 0)
    {
      foreach ($array as $name=>$value)
      {
        $name = strtolower(trim($name));
        if (!empty($name))
        {
          $object->$name = $value;
        }
      }
    }
    return $object;
  }
endif;
?>