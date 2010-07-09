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
