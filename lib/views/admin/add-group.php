<div class="wrap">
  <form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="advertisement[id]" value="<?php echo $group -> ID;?>" />
    <input type="hidden" name="advertisement[a]" value="<?php echo $action;?>" />
    <h2>Group</h2>
    <div id="poststuff" class="postbox">
      <div class="inside">
        <p>
          <label for="advertisement[name]">Name</label><br />
          <input type="text" name="advertisement[name]" value="<?php echo $group->name; ?>" />
        </p>
        <p>
          <label for="advertisement[slug]">Slug</label><br />
          <input type="text" name="advertisement[slug]" value="<?php echo $group->slug; ?>" />
        </p>
        <p>
          <label for="advertisement[order]">Order</label><br />
          <input type="text" name="advertisement[order]" value="<?php echo $group->order; ?>" />
        </p>
      </div>
    </div>
    <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />&nbsp;
    </p>
  </form>
  <h2>Related Ads</h2>
  <div id="poststuff" class="postbox">
    <?php include('_list.php');?>
  </div>
</div>