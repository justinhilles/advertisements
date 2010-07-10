<?php

if(!function_exists('get_advertisements')):
  function get_advertisements()
  {
    global $Ad;
    return $Ad -> Find();
  }
endif;

if(!function_exists('getAdBySlug')):
  function getAdBySlug()
  {
    global $Ad;
    return $Ad -> Find();
  }
endif;


?>