<?php

$model = 'Group';

require_once('Base.class.php');

if(!class_exists('Group'))
{
  class Group extends AdvertisementBase
  {

    var $table = "wp_advertisements_groups";

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