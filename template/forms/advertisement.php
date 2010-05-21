<div class="wrap">
  <form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="ad_id" value="<?php echo $ad_id;?>" />
    <input type="hidden" name="type" value="event" />
    <input type="hidden" name="action" value="<?php echo $action;?>" />
    <?php wp_nonce_field($_REQUEST['page']); ?>
    <div class="icon32" id="icon-edit"><br/></div><h2><?php echo ucfirst($action); ?> Advertisement</h2>
    <div id="poststuff" class="postbox">
      <h3>Advertisement Setup</h3>
      <div class="inside">
        <p>
          <label for="title">Name</label><br />
          <input type="text" name="data[name]" value="<?php echo $ad->name; ?>" />
        </p>
        <p>
          <label for="data[url]">URL</label><br />
          <input type="text" name="data[url]" value="<?php echo $ad->url; ?>" />
        </p>
        <!--<p>
          <label for="data[order]">Order</label><br />
          <input type="text" name="data[order]" value="<?php echo $ad->order; ?>" />
        </p>-->
        <p>
          <label for="File">Upload</label><br />
          <input type="file" name="File" />
        </p>
        <p>
          <?php
          if(!empty($ad->file_url)):
                            $filename = explode(".",basename($ad->file_url));
          $dir = dirname($ad->file_url);
                           if(file_exists(dirname($ad->file_path) . '/' . $filename[0] . '-228x150.jpg')){
            $filename = $dir . '/' . $filename[0] . '-228x150.jpg';
          } else {
            $filename = $ad->file_url;
          }?>
            <img src="<?php echo $filename;?>" />
          <?php endif;?>
        </p>
      </div>
    </div>
    <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />&nbsp;
      <a class="button" href="<?php echo $_SERVER['HTTP_REFFERER'];?>">Back</a>
    </p>
  </form>
</div>