<?php

/**
 * Description of BasePluginclass
 *
 * @author justinhilles
 */
class AdvertisementBasePlugin {

  const DATA = 'data';

  public function __construct()
  {
    if (!session_id())
    {
      session_start();
    }
    date_default_timezone_set('UTC');
  }

  public function versionCheck()
  {
    global $wp_version;
    if(version_compare($wp_version,"2.5","<"))
    {
      exit($this->EXTMSG);
    }
    return true;
  }

  public function getVars()
  {
    global $wp_query;
    $name = constant(get_class($this) . '::DATA');
    $data = array();
    $data[] = $wp_query-> query_vars;
    $data[] = $_GET;
    $data[] = (isset($_REQUEST[$name]) ? $_REQUEST[$name] : array());
    array_walk_recursive($data, array($this, 'setVar'));
    $this -> vars['files'] = self::getFiles($name);
  }
  
  public function setAdmin()
  {
    if(isset($this -> vars['page']))
    {
      add_action('admin_print_styles',      array(&$this, 'adminStyles'    ));
      add_action('admin_print_scripts',     array(&$this, 'adminScripts'   ));
    }
  }
  
  public static function getFiles( $name )
  {
    if(isset($_FILES[$name]))
    {
      $ret_data = array();
      foreach($_FILES[$name] as $field => $values)
      { 
        $i = 0;
        foreach($values as $f => $v)
        { 
          $ret_data[$f][$field] = $v;
          $i++;
        }
      }
      return $ret_data;
    }
    return false;
  }

  public function setVar( $value , $key )
  {
    $this -> vars[$key] = $value;
  }

  public function install()
  {
    if($sql = file_get_contents($this -> PLUGINPATH . '/data/sql/install.sql'))
    {
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);
      include($this -> PLUGINPATH . '/data/install.php');
    }
  }

  public function frontStyles()
  {
    if(isset($this -> frontStylesheets))
    {
      foreach($this -> frontStylesheets as $name => $path)
      {
        wp_register_style($name, $path);
        wp_enqueue_style($name);
      }
    }
  }

  public function frontScripts()
  {
    if(isset($this -> frontJavascripts))
    {
      foreach($this -> frontJavascripts as $name => $path)
      {
        wp_deregister_script($name);
        wp_register_script($name, $path, false, '');
        wp_enqueue_script($name);
      }
    }
  }

  public function frontView( $shortcode = false)
  {
    if(isset($this -> page))
    {
      if(file_exists($this -> FRONTTEMPLATES . $this -> page . '.php'))
      {
        extract($GLOBALS);
        extract((array) $this);
        include( $this -> FRONTTEMPLATES . $this -> page . '.php');
        if(!$shortcode)
        {
          exit;
        }
      }
    }
  }

  public function frontAction()
  {

  }

  public function adminScripts()
  {
    if(isset($this -> adminJavascripts))
    {
      foreach($this -> adminJavascripts as $name => $path)
      {
        wp_deregister_script($name);
        wp_register_script($name, $path, false, '');
        wp_enqueue_script($name);
      }
    }
  }

  public function adminStyles()
  {
    if(isset($this -> adminStylesheets))
    {
      foreach($this -> adminStylesheets as $name => $path)
      {
        wp_register_style($name, $path);
        wp_enqueue_style($name);
      }
    }
  }

  public function adminView()
  {
    if(file_exists($this -> ADMINTEMPLATES . $this -> page . '.php'))
    {
      extract($GLOBALS);
      extract((array) $this);
      include( $this -> ADMINTEMPLATES . $this -> page . '.php');
    }
    else
    {
      echo "<h3>Page Template Not Found</h3><p>Please create file: " . $this ->ADMINTEMPLATES . "<b>" . $this -> page . ".php</b>";
    }
  }
}
?>