<?php

$model = 'Ad';

require_once('Base.class.php');

if(!class_exists('Ad'))
{
  class Ad extends AdvertisementBase
  {

    var $table = "wp_advertisements";

  }
}

$$model = new $model;
?>