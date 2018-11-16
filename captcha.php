<?php
	header('Content-type: image/jpeg');
	session_start();
	$captchanum = rand(1000,9999);
	$_SESSION['code'] = $captchanum;
	$fontsize = 30;
	$width = 110;
	$height = 40;
	$image = imagecreate($width, $height);
	imagecolorallocate($image, 89, 89, 89);
	$textcolour = imagecolorallocate($image, 1, 1, 1);
	//$font = dirname('_FILE_') . 'C:\xampp\htdocs\Webdev2\Project\Ubuntu.ttf';
	imagettftext($image, $fontsize, 0, 15, 30, $textcolour, 'C:\xampp\htdocs\Webdev2\Project\Ubuntu.ttf', $captchanum);
	imagejpeg($image);
?>