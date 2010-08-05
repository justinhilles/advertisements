<?php
$PLUGIN = 'Advertisement';

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

include(dirname(__FILE__) . '/lib/helpers/frontHelper.php');
include(dirname(__FILE__) . '/lib/helpers/baseHelper.php');
include(dirname(__FILE__) . '/lib/helpers/wordpressHelper.php');

require_once(dirname(__FILE__) . '/lib/models/Ad.class.php');
require_once(dirname(__FILE__) . '/lib/models/Group.class.php');
require_once(dirname(__FILE__) . '/lib/BasePlugin.class.php');

//Avoid name collisions.
if( !class_exists($PLUGIN))
{
  class Advertisement extends AdvertisementBasePlugin{
    
    const DATA = 'advertisement';
    const FILE = __FILE__;

    var $pages = array(
       'advertisement'  => '',
       'list-ads'       => 'List Ads',
       'add-ad'         => 'Add Ad',
       'edit-ad'        => '',
       'list-groups'    => 'List Groups',
       'add-group'      => 'Add Group',
       'edit-group'     => '',
       'deactivate-ad'  => '',
       'activate-ad'    => ''
    );

    function __construct()
    {
      parent::__construct();
      $this -> wp = new AdvertisementWordpressHelper();
    }

    function __toString()
    {
      return $this -> render();
    }

    function router()
    {
      parent::router();
      if(!is_admin())
      {
        add_shortcode('advertisements',   array(&$this, 'render'));
      }
    }

    function adminMenu()
    {
      add_menu_page('Advertisement', 'Advertisement', 10 , 'advertisement', array(&$this, 'adminView'));
      foreach($this -> pages as $slug => $title)
      {
        add_submenu_page( 'advertisement', $title, $title, 10, $slug, array(&$this, 'adminView'));
      }
    }

    function adminAction()
    {
      if(isset($this -> vars['a']) && $action = $this -> vars['a'])
      {
        global $Ad, $Group;
        switch( $action )
        {
          case 'add-ad':
            if($file = $this -> wp -> handleUpload($this -> vars['files']['upload']))
            {
              $Ad -> data['file_path'] = $file['file'];
              $Ad -> data['file_url']  = $file['url'];
              $Ad -> data['file_type'] = $file['type'];
              $id = $Ad -> Insert();
              wp_redirect(admin_url('admin.php?page=edit-ad&id=' . $id));
            }
          break;
          case 'edit-ad':
            $Ad -> setId($this->vars['id']);
            if(!empty($this -> vars['files']['upload']['name']) && $file = $this -> wp -> handleUpload($this -> vars['files']['upload']))
            {
              $Ad -> data['file_path'] = $file['file'];
              $Ad -> data['file_url']  = $file['url'];
              $Ad -> data['file_type'] = $file['type'];
            }
            $Ad -> Update();
          break;
          case 'delete-ad':
            $Ad -> setId($this->vars['id']);
            $Ad -> Delete();
            wp_redirect(admin_url('admin.php?page=list-ads'));
          break;
          case 'activate-ad':
            $Ad -> setId($this -> vars['id']);
            $Ad -> data['status'] = 'active';
            $Ad -> Update();
            wp_redirect(admin_url('admin.php?page=list-ads'));
          break;
          case 'deactivate-ad':
            $Ad -> setId($this -> vars['id']);
            $Ad -> data['status'] = 'pending';
            $Ad -> Update();
            wp_redirect(admin_url('admin.php?page=list-ads'));
          break;
          case 'add-group':
              if($id = $Group -> Insert())
              {
                wp_redirect(admin_url('admin.php?page=edit-group&id=' . $id));
              }
          break;
        }
      }
    }

    function adminController()
    {
      if(isset($this -> vars['page']) && $this -> page = $this->vars['page'])
      {
        $this -> action = $this -> page;
        global $Ad, $Group;
        switch( $this -> page )
        {
          case 'list-ads':        
            $this -> ads = $Ad -> Find();
          break;
          case 'list-groups':
            $this -> groups = $Group -> Find();
          break;
          case 'add-ad':
            $this -> ad = $Ad -> _New();
          break;
          case 'edit-ad':
            $this -> ad = $Ad -> findById( $this->vars['id'] );
          break;
           case 'edit-group':
            $this -> group = $Group -> findById( $this->vars['id'] );
            $this -> ads = $Ad -> find(array('field' => 'group_id', 'value' => $this -> group -> ID, 'sort' => array('order','asc')));
          break;
        }
      }
    }

    public function frontAction()
    {

    }

    public function frontController()
    {
      
    }

    function render( $options = array() )
    {
      global $wp_query, $wpdb, $post, $Ad;
      $ads = $Ad->Find();
      shuffle($ads);
      $ads = array_slice($ads,0,5);
      $ret_data = " ";
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
}

if($$PLUGIN = new $PLUGIN)
{
  register_activation_hook(__FILE__, array(&$$PLUGIN,'install'));
}
?>