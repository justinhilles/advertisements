<?php

/*
Plugin Name: Advertisement
Description: Ad Manager
Version: 0.1
Author: Justin Hilles
Author URI: http://www.justinhilles.com

Copyright 2009  Justin Hilles  (email : justin@justinhilles.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('EXIT_MSG','Portfolio requires WordPress 2.5 or newer.');
define('PLUGIN_URL','/' . PLUGINDIR .'/'. dirname(dirname( plugin_basename(__FILE__))));
define('PLUGIN_PATH', WP_PLUGIN_DIR . '/' . dirname(dirname(plugin_basename(__FILE__))));
define('DB_OPTION','portfolio_options');
define('DEFAULT_PARENT_SLUG','portfolio');

require_once('lib/functions.php');
require_once('lib/html.class.php');
require_once('lib/ad.class.php');

//Avoid name collisions.
if( !class_exists('Advertisement')):
  class Advertisement{

    private static $table;
    private static $max;
    public $wpdb;

    function __construct()
    {
      session_start();
      global $wpdb;
      $this->wpdb = $wpdb;
      $this->table[] = $this->wpdb->prefix . "advertisements";
      $this->vars = get_vars();
      $this->error = new WP_Error();
      $this->init();
    }

    function __toString(){
      return $this -> render();
    }

    function init()
    {
      if($this->version_check())
      {
        if(is_admin())
        {
          add_action('admin_init',          array(&$this, 'action'));
          add_action('admin_menu',          array(&$this, 'admin_menu'));
        }
        else
        {
          add_shortcode('advertisements',   array(&$this, 'display'));
          add_action('wp_print_styles',     array(&$this, 'public_styles'));
          add_action('wp_print_scripts',    array(&$this, 'public_scripts'));
        }
      }
    }

    function public_scripts()
    {
      global $post;
      wp_deregister_script( 'jquery' );
      wp_register_script(   'jquery', 'http://code.jquery.com/jquery-1.4.2.min.js', false, '' );
      wp_register_script(   'jqueryui','http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js', false, '' );
      wp_enqueue_script(    'jquery');
      wp_enqueue_script(    'jqueryui');
    }

    function admin_scripts()
    {
      wp_deregister_script( 'jquery' );
      wp_register_script(   'jquery', 'http://code.jquery.com/jquery-1.4.2.min.js', false, '' );
      wp_register_script(   'admin-application',  PLUGIN_URL . '/public/js/admin-application.js', false, '' );
      wp_register_script(   'jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js', false, '' );
      wp_enqueue_script(    'jquery' );
      wp_enqueue_script(    'jquery-ui' );
      wp_enqueue_script(    'admin-application' );
    }

    function public_styles()
    {
    }

    function admin_styles()
    {

    }

    function admin_menu()
    {
      add_menu_page('Advertisement', 'Advertisement', 10 , 'advertisement', array(&$this, 'page'));
      add_submenu_page( 'advertisement', 'List Ads', 'List Ads', 10 , 'list-ads', array(&$this, 'page'));
      add_submenu_page( 'advertisement', 'Add Ad', 'Add Ad', 10 , 'add-ad', array(&$this, 'page'));
      add_submenu_page( 'advertisement', '', '', 10 , 'edit-ad', array(&$this, 'page'));
    }

    function action()
    {
      if(isset( $this->vars['action'] )):
        $action = $this->vars['action'];
        $nonce=$_REQUEST['_wpnonce'];
        if(wp_verify_nonce($nonce, $_REQUEST['page'])):
        switch( $action )
        {
          case 'options':
            $this->options();
          break;
          case 'add':
            global $Ad;
            if($ad = $Ad->Insert())
              wp_redirect(admin_url('admin.php?page=edit-ad&ad_id=' . $ad));
          break;
          case 'edit':
            global $Ad;
            $ad_id = $this->vars['ad_id'];
            $Ad->Update( $ad_id );
          break;
        }
        endif;
      else:
        $this->vars['action'] = (isset( $this->vars['post'] ) ? 'edit' : 'add' );
      endif;
    }

    function page()
    {
      add_action('admin_print_scripts', array(&$this, 'admin_scripts'));
      add_action('admin_print_styles',  array(&$this, 'admin_styles'));
      $page = $this->vars['page'];
      $action = $this->vars['action'];
      switch( $page ){
        case 'index':
          include('template/index.php');
        break;
        case 'list-ads':
          global $Ad;
          $ads = $Ad->Find();
          include('template/list.php');
        break;
        case 'add-ad':
          global $Ad;
          $action = 'add';
          $ad = $Ad -> _New();
          include('template/forms/advertisement.php');
        break;
        case 'edit-ad':
          global $Ad;
          $action = 'edit';
          $ad_id = $this->vars['ad_id'];
          $ad = $Ad->Find( $ad_id );
          include('template/forms/advertisement.php');
        break;
      }
    }

    function install()
    {
      $sql = "CREATE TABLE `" . $this->table[0] . "` (
      `ID` mediumint(9) NOT NULL AUTO_INCREMENT ,
      `name` text NOT NULL ,
      `status` varchar(40) NOT NULL default 'pending' ,
      `url` text NOT NULL ,
      `order` tinyint NOT NULL ,
      `open` datetime NOT NULL default '0000-00-00 00:00:00' ,
      `close` datetime NOT NULL default '0000-00-00 00:00:00' ,
      `content` text NOT NULL,
      `file_path` text NOT NULL,
      `file_url` text NOT NULL,
      `file_type` text NOT NULL,
      PRIMARY KEY  (`ID`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);
    }

    function version_check()
    {
      global $wp_version;
      if(version_compare($wp_version,"2.5","<"))
      {
        exit(EXIT_MSG);
      }
      return true;
    }

    function render( $options = array() )
    {
      global $wp_query, $wpdb, $post, $Ad;
      $ads = $Ad->Find();
      shuffle($ads);
      $ads = array_slice($ads,0,5);
      $ret_data = null;
      foreach($ads as $ad )
      {
        if(!empty($ad->file_url)):
          $filename = explode(".",basename($ad->file_url));
          $dir = dirname($ad->file_url);
          if(file_exists(dirname($ad->file_path) . '/' . $filename[0] . '-228x150.jpg')){
            $filename = $dir . '/' . $filename[0] . '-228x150.jpg';
          } else {
            $filename = $ad->file_url;
          }
          $ret_data .= '<li><a href="' . urlencode($ad->url) . '" target="_blank"><img src="' . $filename . '" alt="ad" /></a></li>';
        endif;
      }
      return $ret_data;
    }
  }	//END Registration Class
endif;

if(class_exists('Advertisement')):
  $Advertisement = new Advertisement();
  if(isset($Advertisement)){register_activation_hook(__FILE__, array(&$Advertisement,'install'));}
endif;
?>