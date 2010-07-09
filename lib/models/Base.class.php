<?php

/**
 * Description of Baseclass
 *
 * @author justinhilles
 */
class AdvertisementBase {

  private $id;
  const DATA = 'advertisement';
  
  var $primary_key = 'ID';

  public function __construct()
  {
    $this -> setFields();
    $this -> setData();
  }

  protected function setData()
  {
    global $wp_query;
    $this -> data = array();
    $name = constant(get_class($this) . '::DATA');
    $data = array();
    $data[] = (isset($wp_query -> query_vars) ? $wp_query-> query_vars: null);
    $data[] = $_GET;
    $data[] = (isset($_REQUEST[$name]) ? $_REQUEST[$name] : array());
    array_walk_recursive($data, array($this, 'setVar'));
    $this -> data = (is_array($this -> vars) ? array_intersect_key( $this -> vars, $this -> fields ) : array());
  }

  protected function getData()
  {
    if(isset($this -> data['password']))
    {
        $this -> data['password'] = md5($this -> data['password']);
    }
    return $this -> data;
  }

  protected function setFields(){
    global $wpdb;
    $this -> fields = array();
    $sql = 'SHOW COLUMNS FROM ' .  $this -> table;
    $fields =  $wpdb->get_results($wpdb -> prepare($sql));
    foreach($fields as $field){$this->fields[$field->Field] = $field->Field;}
  }

  protected function setVar( $value , $key )
  {
    $this -> vars[$key] = $value;
  }

  public function setId( $id )
  {
    $this -> id = $id;
  }

  public function _New()
  {
    return ad_a2o(array_fill_keys(array_keys($this -> fields), ''));
  }

  public function Insert()
  {
    global $wpdb;
    $this -> data['created_at'] = date("Y-m-d H:i:s");
    $wpdb -> insert( $this -> table, $this -> getData());
    return $wpdb->insert_id;
  }

  public function Update()
  {
    global $wpdb;
    return $wpdb->update( $this -> table, $this -> getData(), array($this -> primary_key => $this -> id ) );
  }

  public function Delete()
  {
    global $wpdb;
    $sql = "DELETE FROM $this->table WHERE `" . $this -> primary_key . "` = '" . $this -> id . "'";
    return $wpdb->query($wpdb->prepare($sql));
  }

  public function Find( $id = null )
  {
    global $wpdb;
    if(!empty($id))
    {
      $sql = "SELECT * FROM $this->table WHERE `" . $this -> primary_key . "` = '" . $id . "'";
      return $wpdb->get_row($wpdb -> prepare($sql));
    }
    else
    {
      $p = (isset($_GET['paged']) ? $_GET['paged'] - 1 : 0 );
      $num = (isset($GET['num'])? $_GET['num'] : 25 );
      $start = $p * $num;
      $sql = "SELECT * FROM $this->table LIMIT $start,$num";
      return $wpdb->get_results($sql);
    }
  }

  function findByField( $field , $value )
  {
    global $wpdb;
    $sql = "SELECT * FROM `" . $this->table ."` WHERE `" . $field ."` = '" . $value . "'";
    return $wpdb->get_row($wpdb -> prepare($sql));
  }

  function paginate()
  {
    global $wpdb;
    $sql = "SELECT * FROM $this->table";
    $total = $wpdb->query($wpdb->prepare($sql));
    $args = array(	'base' => add_query_arg( 'paged', '%#%' ),
                                    'format' => '?paged=%#%',
                                    'prev_text' => __('&laquo;'),
                                    'next_text' => __('&raquo;'),
                                    'total' => ceil( $total / 25 ),
                                    'current' => (isset($_GET['paged']) ? $_GET['paged'] : 1));
    $ret_data = paginate_links( $args );
    return $ret_data;
  }
}
?>