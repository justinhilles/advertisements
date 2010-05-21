<?php

require_once( '../../../../../wp-load.php' );

$post_id = $_REQUEST['post'];
if(!empty($post_id)){
	$posts[] = get_portfolio( $post_id );
} else {
	$posts = get_all_portfolio();
}

header('Content-type: text/css'); 
?>

<? foreach($posts as &$post):foreach( $post->attachments as &$attachment):?>
ul.thumbs li a.image-<?php echo $attachment->ID;?> {
	background-image: url(<?php echo $attachment->sprite;?>);
}
<?php endforeach;endforeach;?>