<?php

$model = 'Ad';

require_once('Base.class.php');

if(!class_exists($model))
{
  class Ad extends AdvertisementBase
  {
    const DATA = 'advertisement';

    var $table = "wp_advertisements";
    var $is_sluggable = true;
    var $is_statusable = true;

  }
}

$$model = new $model;
?>