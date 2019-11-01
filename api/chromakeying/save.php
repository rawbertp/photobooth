<?php
header('Content-Type: application/json');

require_once('../../lib/config.php');
require_once('../../lib/db.php');

if($config['file_format_date'] == true) {
	$file = date('Ymd_His').'.jpg';
} else {
	$file = md5(time()).'.jpg';
}

$filename_photo = $config['foldersAbs']['images'] . DIRECTORY_SEPARATOR . $file;
$filename_thumb = $config['foldersAbs']['thumbs'] . DIRECTORY_SEPARATOR . $file;

$img = $_POST['imgData'];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);
$image = imagecreatefromstring($data);
imagejpeg($image, $filename_photo, $config['jpeg_quality_image']);

$image = ResizeJpgImage($image, 500, 500);
imagejpeg($image, $filename_thumb, $config['jpeg_quality_thumb']);

imagedestroy($image);

function ResizeJpgImage($image, $max_width, $max_height)
{
	$old_width  = imagesx($image);
	$old_height = imagesy($image);
	$scale      = min($max_width/$old_width, $max_height/$old_height);
	$new_width  = ceil($scale*$old_width);
	$new_height = ceil($scale*$old_height);
	$new = imagecreatetruecolor($new_width, $new_height);
	imagecopyresampled($new, $image, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);
	return $new;
}

// insert into database
appendImageToDB($file);

// send imagename to frontend
echo json_encode(array('success' => true, 'filename' => $file));
?>