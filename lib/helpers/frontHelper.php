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
    $group =  $Group -> findOneBy(array('where' => array('slug' => $slug)));
    return $Ad -> find(array('where' => array('group_id' => $group -> ID), 'sort' => array('created_at','desc'), 'status' => true));
  }
endif;


if(!function_exists('advertisements'))
{
 function advertisements( $slug = null, $ads = array())
 {
   if(!is_null($slug))
   {
     $ads = getGroupBySlug($slug);
   }
   else
   {
      $ads = get_advertisements();
   }
 }
}