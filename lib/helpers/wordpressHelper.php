<?php 

class AdvertisementWordpressHelper {

	public static function post( array $data, $id = null)
	{
		if(!is_null($id))
		{
			$data['ID'] = $id;
			$result = wp_update_post($data);
		} 
		else 
		{
			$id = wp_insert_post($data);
		}
		global $wpdb;
		$fields = self::getFields($wpdb ->posts);
		if(is_array($fields))
		{
			foreach( $data as $field => $value )
			{
				if(!array_key_exists($field, $fields))
				{
					if( !update_post_meta( $id , $field , $value ) )
					{
						add_post_meta( $id , $field , $value );
					}
				}	
			}
		}
		return $id;
	}
	
	public static function redirect_to( $path )
	{
		wp_redirect(get_option('siteurl') . $path );
		exit;
	}
	
	public static function findPostByField( $field, $value )
	{
		global $wpdb;
		$sql = "SELECT `ID` FROM `$wpdb->posts` WHERE `" . $field . "` = '" . $value . "'";
		return $wpdb->get_row( $wpdb -> prepare( $sql ));
	}
	
	public static function getAttachments( $id , $type = 'image/jpeg' ){
		$args = array(
			'post_type' => 'attachment',
			'numberposts' => -1,
			'post_status' => null,
			'post_parent' => $id,
			'post_mime_type'=> $type
		); 
		return get_posts($args);
	}
	
	public static function attachFile( $id, $file )
	{
		$url = $file['url'];
		$attachment = array(
			'post_mime_type' => $file['type'] ,
			'guid' => $url ,
			'post_parent' => $parent_id ,
			'post_title' => $_REQUEST['title'] ,
			'post_content' => $_REQUEST['description'] ,		
		);
		$attach_id = wp_insert_attachment( $attachment , $url , $id );
		$attach_data = wp_generate_attachment_metadata( $attach_id , $url );
		wp_update_attachment_metadata( $attach_id , $attach_data );
		return $file;
	}	
	
	public static function attachPhoto( $id , $file,  $size = 'medium' )
	{
		if($file = self::ifImageUpload( $file ))
		{
			$image_url = $file['url'];
			$resized = image_make_intermediate_size($file['file'],get_option("{$size}_size_w"),get_option("{$size}_size_h"),get_option("{$size}_crop"));	
			return self::attachFile($id, $file);
		}
		return false;
	} 
	
	public static function handleUpload( $file )
	{																			
		return wp_handle_upload( $file , array('test_form' => false));
	}

	public static function ifImageUpload( $image ){
		// check if image				
		if (file_is_displayable_image( $image['tmp_name'] )){
			return self::handleUpload($image);															
		}
		return false;
	}
	
	public static function getPostCustoms( $id )
	{
		if($keys = get_post_custom_keys( $id ))
		{
			$ret_data = array();
			foreach ( $keys as $int => $key ) 
			{
				$ret_data[ $key ] = get_post_meta($id, $key, true);
			}
			return $ret_data;
		}
		return false;
	}
	
	public static function getFields( $table ){
			global $wpdb;
			$sql = 'SHOW COLUMNS FROM ' .  $table;
			$fields =  $wpdb->get_results($wpdb -> prepare($sql));
			foreach($fields as $field){$temp[$field->Field] = $field->Field;}
			return $temp;
	}

    public function findPostsAsOptionsArray()
    {
      global $wpdb;
      $sql = "SELECT * FROM $wpdb->posts WHERE post_type= 'page'";
      foreach($wpdb->get_results($sql) as $page)
      {
        if(!empty($page -> post_name))
        {
          $pages[$page->ID] = ucfirst($page -> post_type) . ' - ' . ucfirst($page -> post_name);
        }
      }
      return $pages;
    }
}
?>