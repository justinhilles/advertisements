<div class="wrap">
  <form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="advertisement[id]" value="<?php echo $ad->ID;?>" />
    <input type="hidden" name="advertisement[a]" value="<?php echo $action;?>" />
    <h2>Ad</h2>
    <div id="poststuff" class="postbox">
      <div class="inside">
        <p>
          <label for="advertisement[name]">Name</label><br />
          <input type="text" name="advertisement[name]" value="<?php echo $ad->name; ?>" />
        </p>
        <p>
          <label for="advertisement[url]">URL</label><br />
          <input type="text" name="advertisement[url]" value="<?php echo $ad->url; ?>" />
        </p>
        <p>
          <label for="advertisement[slug]">Slug</label><br />
          <input type="text" name="advertisement[slug]" value="<?php echo $ad->slug; ?>" />
        </p>
        <p>
          <label for="advertisement[content]">Content</label><br />
          <input type="text" name="advertisement[content]" value="<?php echo $ad->content; ?>" />
        </p>
        <p>
          <label for="advertisement[order]">Order</label><br />
          <input type="text" name="advertisement[order]" value="<?php echo $ad->order; ?>" />
        </p>
        <p>
          <label for="advertisement[group_id]">Group</label><br />
          <?php echo ad_select_tag('advertisement[group_id]', $Group -> findAsOptionsArray(), $ad -> group_id );?>
        </p>
        <p>
          <label for="advertisement[post_id]">Link to Page or Post?</label><br />
          <?php echo ad_select_tag('advertisement[post_id]', $wp -> findPostsAsOptionsArray(), $ad -> post_id );?>
        </p>
        <p>
          <label for="advertisement[attachment_id]">Link to Media?</label><br />
          <?php echo ad_select_tag('advertisement[attachment_id]', $wp -> findAttachmentsAsOptionsArray(), $ad -> attachment_id );?>
        </p>
        <p>
          <label for="advertisement[upload]">Upload</label><br />
          <input type="file" name="advertisement[upload]" />
        </p>
        <p><?php include('_files.php');?></p>
      </div>
    </div>
    <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />&nbsp;
    </p>
  </form>
</div>