<?php
if($ads)
{
  foreach($ads as $ad)
  {
    link_to_page(get_post($ad->post_id)->post_name, '<img class="png" width="203" height="102" src="' . $ad -> file_url . '" alt="" />');
  }
}