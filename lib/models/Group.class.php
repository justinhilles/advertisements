<?php

$model = 'Group';

require_once('Base.class.php');

if(!class_exists('Group'))
{
  class Group extends AdvertisementBase
  {
    const DATA = 'advertisement';
    
    var $table = "wp_advertisements_groups";
    var $is_sluggable = true;
    var $sluggable_field = 'name';
    var $is_statusable = true;

    public function findAsOptionsArray()
    {
      $groups = array();
      foreach($this -> Find() as $group)
      {
        $groups[$group -> ID]  = $group -> name;
      }
      return $groups;
    }
  }
}

$$model = new $model;
?>