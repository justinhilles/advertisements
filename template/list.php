<h2>List</h2>
<div class="wrap">
  <table class="widefat post fixed" cellspacing="0">
    <thead>
      <tr>
        <th width="20">ID</th>
        <th>Name</th>
        <th>URL</th>
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
      <?php if($ads):?>
        <?php foreach($ads as $ad): ?>
          <tr>
            <td><?php echo $ad->ID; ?></td>
            <td><?php echo $ad->name ?></td>
            <td><?php echo $ad->url; ?></td>
            <td><?php echo $ad->status; ?></td>
            <td>
            <a href="<?php echo admin_url('admin.php?page=edit-ad&ad_id=' . $ad->ID);?>">Edit</a> /
            <a href="<?php echo admin_url('admin.php?action=delete&page=list-page&ad_id=' . $ad->ID . '&_wpnonce=' . $nonce);?>">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif;?>
    </tbody>
  </table>
</div>