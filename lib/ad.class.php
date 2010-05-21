<?php

if(!class_exists('Ad')):
  class Ad {

    var $table = "wp_advertisements";
    var $data;

    function __construct()
    {
      if(isset($_POST['data']))
      {
        $this->data = $_REQUEST['data'];
      }
    }

    function _New()
    {
      $fields = showColumns($this->table);
      $keys = array_keys($fields);
      $ret_data = array_fill_keys($keys, '');
      $ret_data = a2o($ret_data);
      return $ret_data;
    }

    function Find( $id = null ){
      global $wpdb;
      return (!empty($id) ? $wpdb->get_row("SELECT * FROM $this->table WHERE ID = " . $id ) : $wpdb->get_results( "SELECT * FROM $this->table" ));
    }

    function Delete( $id ){
      global $wpdb;
    }

    function Update( $id ){
      global $wpdb;
      $file = $this->Upload();
      if($file)
      {
        $this->data['file_path'] = $file['file'];
        $this->data['file_url'] = $file['url'];
        $this->data['file_type'] = $file['type'];
      }
      return $wpdb->update( $this->table, $this->data , array( 'ID' => $id ), array(), array( '%d' ) );
    }

    function Insert(){
      global $wpdb;
      $file = $this->Upload();
      $this->data['file_path'] = $file['file'];
      $this->data['file_url'] = $file['url'];
      $this->data['file_type'] = $file['type'];
      if($wpdb->insert( $this->table , $this->data ))
      return $wpdb->insert_id;
    }

    function Upload(){
      $file = $_FILES['File'];
      $result =  array();
      if(file_is_displayable_image( $file['tmp_name']))
      {
        $overrides = array('test_form' => false);
        $result = wp_handle_upload($file, $overrides);
        $tn = image_make_intermediate_size( $result['file'], 228, 150, true);
      }
      return $result;
    }

  }
endif;

if(class_exists('Ad')):
  $Ad = new Ad();
endif;
?>