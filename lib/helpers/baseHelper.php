<?php

if(!function_exists('ad_messages'))
{
	function ad_messages( $published_id = NULL ){
		$ret_data = NULL;
		if (isset($_SESSION['error']))
		{	
			$ret_data .= '<div id="message" class="error fade"><p>' . $_SESSION['error'] . '</p></div>';
			unset($_SESSION['error']);	
		} 
		else if (isset($_SESSION['message']) && isset($published_id)) 
		{
			$ret_data .= '<div id="message" class="updated fade">';
			$ret_data .= '<p><strong>' . $_SESSION['message'] . '</strong>';
			$ret_data .= '<a href="' . get_permalink($published_id) . '">View post &raquo;</a></p>';
			$ret_data .= '</div>';
			unset($_SESSION['message']);	
		}
		echo $ret_data;
	}
}

if(!function_exists('ad_format_date'))
{
	function ad_format_date( $date )
	{
		$date = explode("/",$date);
		return $date[2] . "," . ($date[0] -1) . "," . $date[1];
	}
}

if(!function_exists('ad_a2o'))
{
	function ad_a2o($array) {
		$object = new stdClass();
		if (is_array($array) && count($array) > 0) {
		  foreach ($array as $name=>$value) {
			 $name = strtolower(trim($name));
			 if (!empty($name)) {
				$object->$name = $value;
			 }
		  }
		}
		return $object;
	}
}

if(!function_exists('ad_select_tag'))
{
	function ad_select_tag( $name , $options = array() , $value , $attributes = NULL , $sort = NULL )
	{
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
}

if(!function_exists('ad_options'))
{	
	function ad_options( $data = array() , $v , $t )
	{
		foreach( $data as $option){$ret_data[$option->$v] = $option->$t;}
		return $ret_data;
	}
}
?>