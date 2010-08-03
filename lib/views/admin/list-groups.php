<h2>List</h2>
<div class="wrap">
  <table class="widefat post fixed" cellspacing="0">
    <thead>
      <tr>
        <th width="20">ID</th>
        <th>Name</th>
        <th>Slug</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th width="20">ID</th>
        <th>Name</th>
        <th>URL</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </tfoot>
    <tbody>
      <?php if($groups):?>
        <?php foreach($groups as $group): ?>
          <tr>
            <td><?php echo $group->ID; ?></td>
            <td><?php echo $group->name ?></td>
            <td><?php echo $group->slug; ?></td>
            <td><?php echo $group->status; ?></td>
            <td>
              <?php echo ($group -> status == 'pending' ? sprintf('<a href="%s">%s</a>',admin_url('admin.php?advertisement[a]=activate-ad&id=' . $group->ID), 'Activate') : sprintf('<a href="%s">%s</a>',admin_url('admin.php?advertisement[a]=deactivate-ad&id=' . $group->ID), 'Deactivate'));?> /
              <a href="<?php echo admin_url('admin.php?page=edit-group&id=' . $group->ID);?>">Edit</a> /
              <a href="<?php echo admin_url('admin.php?advertisement[a]=delete-ad&id=' . $group->ID);?>">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif;?>
    </tbody>
  </table>
</div>