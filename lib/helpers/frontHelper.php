<?php

if(!function_exists('get_advertisements')):
  function get_advertisements()
  {
    global $Ad;
    return $Ad -> Find();
  }
endif;

if(!function_exists('getAdBySlug')):
  function getAdBySlug($slug)
  {
    global $Ad;
    return $Ad -> findOneBy('slug', $slug);
  }
endif;

if(!function_exists('getGroupBySlug')):
  function getGroupBySlug( $slug )
  {
    global $Group, $Ad;
    $group =  $Group -> findOneBy('slug', $slug);
    return $Ad -> findBy('group_id', $group -> ID);
  }
endif;


?>