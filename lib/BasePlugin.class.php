<?php

/**
 * Description of BasePluginclass
 *
 * @author justinhilles
 */
class AdvertisementBasePlugin {

  const JQUERY   = 'http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js';
  const JQUERYUI = 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js';
  const DATA = 'data';

  public function __construct()
  {
    if (!session_id())session_start();
    date_default_timezone_set('UTC');
    $this -> PLUGINURL  = get_bloginfo('url') . '/' . PLUGINDIR .'/'. dirname(plugin_basename(constant(get_class($this) . '::FILE')));
    $this -> PLUGINPATH = trailingslashit(WP_PLUGIN_DIR .'/'. dirname(plugin_basename(constant(get_class($this) . '::FILE'))));
    $this -> AJAXPATH = $this -> PLUGINPATH . 'data/ajax.php';
    $this -> ADMINTEMPLATES = $this -> PLUGINPATH . 'lib/views/admin/';
    $this -> FRONTTEMPLATES = $this -> PLUGINPATH . 'lib/views/front/';
    $this -> adminStylesheets = array('admin' => $this -> PLUGINURL . '/public/css/admin.css');
    $this -> adminJavascripts = array('jquery' => self::JQUERY,'jqueryui' => self::JQUERYUI,'admin' => $this -> PLUGINURL . '/public/js/admin-application.js');
    $this -> frontStylesheets = array();
    $this -> frontJavascripts = array('jquery' => self::JQUERY,'jqueryui' => self::JQUERYUI,'admin' => $this -> PLUGINURL . '/public/js/application.js');
    $this -> router();
  }

  public function router()
  {
    if($this->versionCheck())
    {
      if(is_admin())
      {
        global $Issue;
        add_action('admin_init',              array(&$this, 'getVars'        ));
        add_action('admin_init',              array(&$this, 'setAdminHead'   ));
        add_action('admin_init',              array(&$this, 'adminAction'    ));
        add_action('admin_notices',           array(&$this, 'adminController'));
        add_action('admin_menu',              array(&$this, 'adminMenu'      ));
      }
      else
      {
        add_action('pre_get_posts',           array(&$this, 'getVars'        ));
        add_action('pre_get_posts',           array(&$this, 'frontAction'    ));
        add_action('pre_get_posts',           array(&$this, 'frontController'));
        add_action('pre_get_posts',           array(&$this, 'setFrontHead'   ));
        add_action('template_redirect',       array(&$this, 'frontView'      ));
      }
    }
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

  public function install()
  {
    if($sql = file_get_contents($this -> PLUGINPATH . '/data/sql/install.sql'))
    {
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);
      include($this -> PLUGINPATH . '/data/install.php');
    }
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

  public function setVar( $value , $key )
  {
    $this -> vars[$key] = $value;
  }

  public function setAdminHead()
  {
    if(isset($this -> vars['page']) && array_key_exists($this -> vars['page'], $this -> pages))
    {
      add_action('admin_print_styles',      array(&$this, 'adminStyles'    ));
      add_action('admin_print_scripts',     array(&$this, 'adminScripts'   ));
    }
  }

  public function setFrontHead()
  {
    if(isset($this -> page))
    {
      add_action('wp_print_scripts',        array(&$this, 'frontScripts'   ));
      add_action('wp_print_styles',         array(&$this, 'frontStyles'    ));
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