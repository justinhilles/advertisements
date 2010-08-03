<?php

include(dirname(__FILE__) . "./../helpers/sluggableHelper.class.php");

/**
 * Description of Baseclass
 *
 * @author justinhilles
 */
class AdvertisementBase {

  public $id;
  const DATA = 'data';

  var $pk = 'ID';
  var $is_timestampable = true;
  var $is_sluggable = false;
  var $sluggable_field = 'title';
  var $is_statusable = false;

  public function __construct()
  {
    $this -> wpdb = $GLOBALS['wpdb'];
    $this -> setFields();
    $this -> setData();
    $this -> wp = new AdvertisementWordpressHelper();
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
    if(isset($this -> data['created_at']))
    {
      unset($this -> data['created_at']);
    }
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
    if($this -> is_timestampable)
    {
      $this -> data['created_at'] = date("Y-m-d H:i:s");
    }

    if($this -> is_sluggable)
    {
      if(isset($this -> data[$this -> sluggable_field]))
      {
        $this -> data['slug'] = sluggableHelper::stripText($this -> data[$this -> sluggable_field]);
      }
    }

    $this -> wpdb -> insert( $this -> table, $this -> getData());
    return $this -> wpdb -> insert_id;
  }

  public function Update()
  {
    global $wpdb;
    return $wpdb->update( $this -> table, $this -> getData(), array($this -> pk => $this -> id ) );
  }

  public function Delete()
  {
    global $wpdb;
    $sql = "DELETE FROM {$this->table} WHERE `{$this->table}`.`{$this -> pk}` = '{$this -> id}'";
    return $wpdb->query($wpdb->prepare($sql));
  }

  public function find($options = array())
  {
    $table = (isset($options['table']) ? $options['table'] : $this -> table);

    $sql = "SELECT * FROM `{$table}`";

    if(isset($options['status']))
    {
      $options['where']['status'] = 'active';
    }

    if(isset($options['where']))
    {
      $i = 0;
      foreach($options['where'] as $field => $value)
      {
        if($i > 0)
        {
          $sql .= " AND";
        } else {
          $sql .= " WHERE";
        }
        $sql .= " `{$table}`.`{$field}` = '{$value}'";
        $i++;
      }
    }

    if(isset($options['sort']))
    {
      $sql .= " ORDER BY `{$table}`.`{$options['sort'][0]}` " . strtoupper($options['sort'][1]);
    }
    if(isset($options['limit']))
    {
      if(count($options['limit']) == 2)
      {
        $sql .= " LIMIT {$options['limit'][0]} ,{$options['limit'][1]}";
      }
    }
    if(isset($options['debug']))
    {
      var_dump($sql);exit;
    }
    if(isset($options['row']))
    {
      return $this -> wpdb -> get_row($this -> wpdb -> prepare($sql));
    }
    return $this -> wpdb -> get_results($this -> wpdb -> prepare($sql));
  }

  public function findOneBy( $options = array())
  {
    $options['row'] = true;
    return $this -> find($options);
  }

  public function findById( $id )
  {
    return $this -> findOneBy(array('field' => $this -> pk,'value' => $id));
  }
}
?>